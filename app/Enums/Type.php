<?php

namespace App\Enums;

enum Type: string
{
    case PERSONAL = 'personal';
    case WORK = 'work';

    public static function fromName($name)
    {
        $cases = [
            'PERSONAL' => self::PERSONAL,
            'WORK' => self::WORK,
        ];

        return $cases[$name];
    }

    public static function values(): array
    {
       return array_column(self::cases(), 'value');
    }
}
