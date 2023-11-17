<?php

namespace App\Enums;

enum TaskStatus: string
{
    case TODO = 'todo';
    case IN_PROGRESS = 'in-progress';
    case ON_HOLD = 'on-hold';
    case COMPLETED = 'completed';

    public static function values(): array
    {
       return array_column(self::cases(), 'value');
    }
}
