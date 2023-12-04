<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LifeEvent;
use App\Models\Board;
use App\Models\Task;
use Carbon\CarbonImmutable;
use DateTimeInterface;

class Week extends Model
{
    use HasFactory;

    protected $fillable = [
        'start',
        'end',
        'age',
        'properties',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'properties' => 'json'
    ];

    public function events()
    {
        return $this->hasMany(LifeEvent::class);
    }

    public function board()
    {
        return $this->hasOne(Board::class);
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }

    /**
     * Prepare a date for array / JSON serialization.
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d');
    }

    public static function getWeekByDate($date)
    {
        $date = CarbonImmutable::parse($date, env('APP_TIMEZONE', 'UTC'));

        $week = Week::where('start', '<=', $date)
            ->where('end', '>=', $date)
            ->first();

        return $week;
    }

    public static function current()
    {
        $today = CarbonImmutable::now(env('APP_TIMEZONE', 'UTC'));
        $week = Week::where('start', '<=', $today)
            ->where('end', '>=', $today)
            ->first();

        return $week;
    }

    public static function generateWeeksTimeline($birthday, $deathAge)
    {
        $birthday = CarbonImmutable::create($birthday, env('APP_TIMEZONE', 'UTC'));
        $death = $birthday->addYears($deathAge);

        $weeks = [
            [
                'week' => 0,
                'start' => $birthday,
                'age' => 0,
                'end' => $birthday->addWeeks(1),

            ]
        ];
        while (true) {
            $weekNumber = count($weeks);
            $thisWeekStart = $birthday->addWeeks($weekNumber);
            $thisWeekEnd = $birthday->addWeeks($weekNumber + 1);
            $afterDeath = $thisWeekEnd->greaterThan($death);
            $age = $thisWeekEnd->diffInYears($birthday);

            if ($afterDeath) {
                $thisWeekEnd = $death;
                $age = 90;
            }

            array_push($weeks, [
                'week' => $weekNumber,
                'start' => $thisWeekStart,
                'age' => $age,
                'end' => $thisWeekEnd
            ]);

            if ($afterDeath) {
                break;
            }
        }

        return $weeks;
    }

}
