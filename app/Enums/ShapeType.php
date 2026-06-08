<?php

namespace App\Enums;

use ArchTech\Enums\Values;

enum ShapeType: string
{
    use Values;

    case CONE = 'cone';
    case FLAT_BOTTOM = 'flat-bottom';
    case IMMERSION = 'immersion';
}
