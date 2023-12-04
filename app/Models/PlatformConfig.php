<?php

namespace App\Models;

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
            return CarbonImmutable::parse($this->value, env('APP_TIMEZONE', 'UTC'));
        }

        $value = $this->value;
        settype($value, $this->type);
        
        return $value;
    }

    public static function whereKey($key) {
        return PlatformConfig::where('key', $key)->first();
    }
}
