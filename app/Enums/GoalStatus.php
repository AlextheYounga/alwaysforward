<?php

namespace App\Enums;

enum GoalStatus: string
{
    case ACTIVE = 'active';
    case ABORTED = 'aborted';
    case FAILED = 'failed';
    case COMPLETED = 'completed';
}
