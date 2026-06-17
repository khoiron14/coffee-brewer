<?php

namespace App\Services\Exporter;

use App\Interfaces\ExporterInterface;
use App\Models\Recipe;
use Symfony\Component\HttpFoundation\Response;

class TextExporter implements ExporterInterface
{
    public function export(Recipe $recipe): Response
    {
        $brewerName = $recipe->brewer?->name ?? '-';
        $coffeeName = $recipe->coffee?->name ?? '-';
        $roastLevel = $recipe->coffee?->roast_level ?? '-';

        $text = "☕ *Resep Kopi: {$recipe->name}*\n";
        $text .= "Alat: {$brewerName}\n";
        $text .= "Kopi: {$coffeeName} ({$roastLevel} roast)\n";
        $text .= "Rasio: {$recipe->coffee_weight}g kopi / {$recipe->water_weight}ml air\n";
        $text .= "Suhu: {$recipe->temperature}°C | Grind: " . ucfirst($recipe->grind_size) . "\n\n";
        
        $text .= "*Langkah Seduh:*\n";

        $stepNumber = 1;

        foreach ($recipe->recipeSteps ?? [] as $step) {
            $formattedPourType = ucfirst(str_replace('_', ' ', $step->pour_type));
            
            $text .= "{$stepNumber}. Detik ke-{$step->duration}: Tuang {$step->pour_volume}ml ({$formattedPourType})\n";
            
            if (!empty($step->note)) {
                $text .= "   ↳ Catatan: {$step->note}\n";
            }
            
            $stepNumber++;
        }

        $appName = config('app.name', 'Jurnal Kopi');
        $text .= "\n~ Dibuat dengan {$appName} ~";

        return response()->json([
            'success' => true,
            'text_to_copy' => $text
        ]);
    }
}
