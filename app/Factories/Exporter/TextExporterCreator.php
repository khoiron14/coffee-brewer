<?php

namespace App\Factories\Exporter;

use App\Interfaces\ExporterInterface;
use App\Services\Exporter\TextExporter;

class TextExporterCreator extends ExporterCreator
{
    protected function createExporter(): ExporterInterface
    {
        return new TextExporter();
    }
}
