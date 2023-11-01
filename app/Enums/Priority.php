<?php

namespace App\Enums;

enum Priority: int
{
    case LOW = 0;
    case NORMAL = 1;
    case HIGH = 2;
    case SUPER = 3;
}
