<?php

namespace App\Enums;

enum LifeEventType: string
{
    case INEVITABLE = 'inevitable';
    case WORK = 'work';
    case PERSONAL = 'personal';

    public static function values(): array
    {
       return array_column(self::cases(), 'value');
    }
}
