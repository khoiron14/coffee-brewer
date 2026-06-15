<?php

namespace App\Enums;

use ArchTech\Enums\Values;

enum ExportType: string
{
    use Values;

    case TEXT = 'text';
    case IMAGE = 'image';
}
