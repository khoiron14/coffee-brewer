<?php

namespace App\Enums;

use ArchTech\Enums\Values;

enum GrindSize: string
{
    use Values;

    case FINE = 'fine';
    case MEDIUM = 'medium';
    case COARSE = 'coarse';
}
