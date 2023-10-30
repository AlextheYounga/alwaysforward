<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonImmutable;
use App\Models\Week;

class LifeEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'week_id',
        'date',
        'title',
        'description',
        'properties',
    ];

    protected $casts = [
        'date' => 'date',
        'properties' => 'json'
    ];

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public static function getQuarterLifeDate()
    {
        $birthday = PlatformConfig::whereKey('birthday')->getValue();
        if ($birthday == null) {
            return null;
        }

        $birthDate = CarbonImmutable::parse($birthday);
        $quarterLife = $birthDate->addYears(25);

        return $quarterLife;
    }

    public static function getBirthDate()
    {
        $birthday = PlatformConfig::whereKey('birthday')->getValue();
        if ($birthday == null) {
            return null;
        }

        return CarbonImmutable::parse($birthday);
    }

    public static function getAge30Date()
    {
        $birthday = PlatformConfig::whereKey('birthday')->getValue();
        if ($birthday == null) {
            return null;
        }

        $birthDate = CarbonImmutable::parse($birthday);
        $age30Date = $birthDate->addYears(30);

        return $age30Date;
    }

    public static function getMidLifeDate()
    {
        $birthday = PlatformConfig::whereKey('birthday')->getValue();
        if ($birthday == null) {
            return null;
        }

        $birthDate = CarbonImmutable::parse($birthday);
        $midlifeDate = $birthDate->addYears(50);

        return $midlifeDate;
    }

    public static function getDeathDate()
    {
        $birthday = PlatformConfig::whereKey('birthday')->getValue();
        $deathAge = PlatformConfig::whereKey('death_age')->getValue();

        if ($birthday == null || $deathAge == null) {
            return null;
        }

        $birthDate = CarbonImmutable::parse($birthday);
        $deathDate = $birthDate->addYears($deathAge);

        return $deathDate;
    }

    public static function getDefaultLifeEventDateMapping()
    {
        $birthday = LifeEvent::getBirthDate();
        $quarterLife = LifeEvent::getQuarterLifeDate();
        $age30 = LifeEvent::getAge30Date();
        $midLife = LifeEvent::getMidLifeDate();
        $deathDate = LifeEvent::getDeathDate();

        $lifeEventDateMapping = [
            'birth' => $birthday,
            'quarterLife' => $quarterLife,
            'age30' => $age30,
            'midLife' => $midLife,
            'death' => $deathDate,
        ];

        return $lifeEventDateMapping;
    }
}
