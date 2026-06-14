<?php

namespace App\Interfaces;

use App\Models\Recipe;
use Symfony\Component\HttpFoundation\Response;

interface ExporterInterface
{
    public function export(Recipe $recipe): Response;
}
