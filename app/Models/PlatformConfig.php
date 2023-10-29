<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformConfig extends Model
{
    use HasFactory;

    protected $table = 'platform_config';

    protected $fillable = [
        'key',
        'value',
        'type',
        'properties',
    ];

    protected $casts = [
        'properties' => 'json',
    ];

    public function getValue() {
        $type = $this->type;

        if ($type == 'date') {
            return Carbon::parse($this->value);
        }

        $value = $this->value;
        settype($value, $this->type);
        
        return $value;
    }

    public static function whereKey($key) {
        return PlatformConfig::where('key', $key)->first();
    }

    public static function getDeathDate() {
        $birthday = PlatformConfig::whereKey('birthday')->getValue();
        $deathAge = PlatformConfig::whereKey('death_age')->getValue();

        if ($birthday == null || $deathAge == null) return null;
        
        $birthDate = CarbonImmutable::parse($birthday);
        $deathDate = $birthDate->addYears($deathAge);

        return $deathDate;
    }
}
