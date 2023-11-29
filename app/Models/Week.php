<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonImmutable;
use App\Models\LifeEvent;
use App\Models\Board;
use App\Models\Task;
use Carbon\Carbon;

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
        'start' => 'date',
        'end' => 'date',
        'properties' => 'json'
    ];

    public function events() {
        return $this->hasMany(LifeEvent::class);
    }

    public function board() {
        return $this->hasOne(Board::class);
    }

    public function tasks() {
        return $this->belongsToMany(Task::class);
    }

    public static function getWeekByDate($date) {
        $timezone = env('APP_TIMEZONE');
        $date = Carbon::parse($date, $timezone);
        
        $week = Week::where('start', '<=', $date)
            ->where('end', '>=', $date)
            ->first();

        return $week;
    }

    public static function current() {
        $timezone = env('APP_TIMEZONE');
        $today = Carbon::now($timezone);
        $week = Week::where('start', '<=', $today)
            ->where('end', '>=', $today)
            ->first();

        return $week;
    }

    public static function generateWeeksTimeline($birthday, $deathAge)
    {
        $timezone = env('APP_TIMEZONE');
        $birthday = CarbonImmutable::create($birthday)->timezone($timezone);
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
