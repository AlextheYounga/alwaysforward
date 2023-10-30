<?php

namespace App\Enums;

enum Status: string
{
    case TODO = 'todo';
    case IN_PROGRESS = 'in-progress';
    case ON_HOLD = 'on-hold';
    case COMPLETED = 'completed';
}
