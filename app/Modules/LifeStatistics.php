<?php

namespace App\Modules;

use Carbon\CarbonImmutable;
use App\Models\Week;
use App\Models\PlatformConfig;
use App\Models\LifeEvent;

class LifeStatistics
{
    public static function getBirthDate()
    {
        $timezone = env('APP_TIMEZONE', 'America/New_York');
        $birthday = PlatformConfig::whereKey('birthday')->getValue();
        if ($birthday == null) {
            return null;
        }

        return CarbonImmutable::parse($birthday)->timezone($timezone);
    }

    public static function getQuarterLifeDate()
    {
        $timezone = env('APP_TIMEZONE', 'America/New_York');
        $birthday = PlatformConfig::whereKey('birthday')->getValue();
        if ($birthday == null) {
            return null;
        }

        $birthDate = CarbonImmutable::parse($birthday)->timezone($timezone);
        $quarterLife = $birthDate->addYears(25);

        return $quarterLife;
    }

    public static function getAge30Date()
    {
        $timezone = env('APP_TIMEZONE', 'America/New_York');
        $birthday = PlatformConfig::whereKey('birthday')->getValue();
        if ($birthday == null) {
            return null;
        }

        $birthDate = CarbonImmutable::parse($birthday)->timezone($timezone);
        $age30Date = $birthDate->addYears(30);

        return $age30Date;
    }

    public static function getMidLifeDate()
    {
        $timezone = env('APP_TIMEZONE', 'America/New_York');
        $birthday = PlatformConfig::whereKey('birthday')->getValue();
        if ($birthday == null) {
            return null;
        }

        $birthDate = CarbonImmutable::parse($birthday)->timezone($timezone);
        $midlifeDate = $birthDate->addYears(50);

        return $midlifeDate;
    }

    public static function getDeathDate()
    {
        $timezone = env('APP_TIMEZONE', 'America/New_York');
        $birthday = PlatformConfig::whereKey('birthday')->getValue();
        $deathAge = PlatformConfig::whereKey('death_age')->getValue();

        if ($birthday == null || $deathAge == null) {
            return null;
        }

        $birthDate = CarbonImmutable::parse($birthday)->timezone($timezone);
        $deathDate = $birthDate->addYears($deathAge);

        return $deathDate;
    }

    public static function getLifeEventDateMapping()
    {
        $birthday = LifeStatistics::getBirthDate();
        $quarterLife = LifeStatistics::getQuarterLifeDate();
        $age30 = LifeStatistics::getAge30Date();
        $midLife = LifeStatistics::getMidLifeDate();
        $deathDate = LifeStatistics::getDeathDate();

        $lifeEventDateMapping = [
            'birth' => $birthday,
            'quarterLife' => $quarterLife,
            'age30' => $age30,
            'midLife' => $midLife,
            'death' => $deathDate,
        ];

        return $lifeEventDateMapping;
    }

    public static function calculateTimeLeftStatistics()
    {
        $timezone = env('APP_TIMEZONE', 'America/New_York');
        $today = CarbonImmutable::now($timezone);
        $events = LifeStatistics::getLifeEventDateMapping();
        $thisWeek = Week::current();

        // Life
        $age = $today->diffAsCarbonInterval($events['birth']);
        $timeLeft = $events['death']->diffAsCarbonInterval($today);
        $totalLife = $events['death']->diffInDays($events['birth']);
        $lifeLived = $today->diffInDays($events['birth']);
        $percentComplete = round($lifeLived / $totalLife * 100, 4);

        $thisYearBirthday = CarbonImmutable::create(
            $today->year,
            $events['birth']->month,
            $events['birth']->day,
            0,
            0,
            0
        )->timezone($timezone);

        // Weeks
        $weekNumber = $thisWeek->id;
        $weeksLeft = Week::all()->last()->id - $thisWeek->id;
        $thisWeekTimeLeft = $thisWeek->end->diffAsCarbonInterval($today);

        // Time Left
        $timeLeft = $events['death']->diffAsCarbonInterval($today);
        $timeUntil30 = $events['age30']->diffAsCarbonInterval($today);
        $timeUntil50 = $events['midLife']->diffAsCarbonInterval($today);
        $thisDayTimeLeft = $today->endOfDay()->diffAsCarbonInterval($today);
        $timeUntilBirthday = $thisYearBirthday->diffAsCarbonInterval($today);

        return [
            "age" => $age->forHumans(['parts' => 4]),
            "timeLeft" => $timeLeft->forHumans(['parts' => 4]),
            "percentComplete" => $percentComplete . "%",
            "timeUntilBirthday" => $timeUntilBirthday->forHumans(['parts' => 4]),
            "timeUntil30" => $timeUntil30->forHumans(['parts' => 4]),
            "timeUntil50" => $timeUntil50->forHumans(['parts' => 4]),
            "weekNumber" =>  $weekNumber,
            "weeksLeft" =>  $weeksLeft,
            "weekTimeLeft" =>  $thisWeekTimeLeft->forHumans(['parts' => 3]),
            "timeLeftToday" => $thisDayTimeLeft->forHumans(['parts' => 3]),
        ];
    }
}
