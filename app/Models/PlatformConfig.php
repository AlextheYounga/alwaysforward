<?php

namespace App\Models;

use Carbon\Carbon;
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
        $timezone = env('APP_TIMEZONE');
        $type = $this->type;

        if ($type == 'date') {
            return Carbon::parse($this->value)->timezone($timezone);
        }

        $value = $this->value;
        settype($value, $this->type);
        
        return $value;
    }

    public static function whereKey($key) {
        return PlatformConfig::where('key', $key)->first();
    }
}
