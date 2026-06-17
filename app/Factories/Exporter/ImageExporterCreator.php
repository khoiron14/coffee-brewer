<?php

namespace App\Factories\Exporter;

use App\Interfaces\ExporterInterface;
use App\Services\Exporter\ImageExporter;

class ImageExporterCreator extends ExporterCreator
{
    protected function createExporter(): ExporterInterface
    {
        return new ImageExporter();
    }
}
