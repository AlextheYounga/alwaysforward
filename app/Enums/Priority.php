<?php

namespace App\Enums;

enum Priority: int
{
    case LOW = 0;
    case NORMAL = 1;
    case HIGH = 2;
    case SUPER = 3;

    public static function fromName($name)
    {
        $cases = [
            'LOW' => self::LOW,
            'NORMAL' => self::NORMAL,
            'HIGH' => self::HIGH,
            'SUPER' => self::SUPER,
        ];

        return $cases[$name];
    }

    public static function values(): array
    {
       return array_column(self::cases(), 'value');
    }
}
