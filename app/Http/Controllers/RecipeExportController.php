<?php

namespace App\Http\Controllers;

use App\Enums\ExportType;
use App\Factories\ExporterFactory;
use App\Http\Controllers\Controller;
use App\Models\Recipe;

class RecipeExportController extends Controller
{
    public function __invoke(Recipe $recipe, ExportType $type)
    {
        $exporter = ExporterFactory::make($type);

        return $exporter->export($recipe);
    }
}
