<?php

namespace App\Enums;

use ArchTech\Enums\Values;

enum RoastLevel: string
{
    use Values;

    case LIGHT = 'light';
    case MEDIUM = 'medium';
    case DARK = 'dark';
}
