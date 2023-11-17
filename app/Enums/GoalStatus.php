<?php

namespace App\Enums;

enum GoalStatus: string
{
    case ACTIVE = 'active';
    case ABORTED = 'aborted';
    case FAILED = 'failed';
    case COMPLETED = 'completed';

    public static function values(): array
    {
       return array_column(self::cases(), 'value');
    }
}
