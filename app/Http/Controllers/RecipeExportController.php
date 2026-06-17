<?php

namespace App\Http\Controllers;

use App\Enums\ExportType;
use App\Factories\Exporter\ExporterCreator;
use App\Factories\Exporter\ImageExporterCreator;
use App\Factories\Exporter\TextExporterCreator;
use App\Http\Controllers\Controller;
use App\Models\Recipe;

class RecipeExportController extends Controller
{
    public function __invoke(Recipe $recipe, ExportType $type)
    {
        $creator = $this->resolveCreator($type);

        return $creator->handle($recipe);
    }

    private function resolveCreator(ExportType $type): ExporterCreator
    {
        return match ($type) {
            ExportType::TEXT => new TextExporterCreator(),
            ExportType::IMAGE => new ImageExporterCreator(),
        };
    }
}
