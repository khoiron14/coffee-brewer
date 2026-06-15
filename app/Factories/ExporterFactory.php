<?php

namespace App\Factories;

use App\Enums\ExportType;
use App\Interfaces\ExporterInterface;
use App\Services\Exporter\TextExporter;
use App\Services\Exporter\ImageExporter;
use InvalidArgumentException;

class ExporterFactory
{
    public static function make(ExportType $type): ExporterInterface
    {
        return match ($type) {
            ExportType::TEXT => new TextExporter(),
            ExportType::IMAGE => new ImageExporter(),
            default => throw new InvalidArgumentException("Format ekspor '{$type}' tidak didukung."),
        };
    }
}
