<?php

namespace App\Factories\Exporter;

use App\Interfaces\ExporterInterface;
use App\Models\Recipe;
use Symfony\Component\HttpFoundation\Response;

abstract class ExporterCreator
{
    abstract protected function createExporter(): ExporterInterface;

    final public function handle(Recipe $recipe): Response
    {
        $recipe->loadMissing([
            'brewer',
            'coffee',
            'recipeSteps',
        ]);

        $exporter = $this->createExporter();

        return $exporter->export($recipe);
    }
}
