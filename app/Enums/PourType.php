<?php

namespace App\Enums;

use ArchTech\Enums\Values;

enum PourType: string
{
    use Values;

    case BLOOM = 'bloom';
    case SPIRAL = 'spiral';
    case CENTER_POUR = 'center-pour';
    case PULSE = 'pulse';
    case STEEP = 'steep';
}
