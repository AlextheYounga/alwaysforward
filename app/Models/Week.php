<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonImmutable;

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

    public static function generateWeeksTimeline($birthday, $deathAge)
    {
        $birthday = CarbonImmutable::create($birthday);
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
            $age = $thisWeekStart->diffInYears($birthday);

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
