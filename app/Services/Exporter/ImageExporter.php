<?php

namespace App\Services\Exporter;

use App\Interfaces\ExporterInterface;
use App\Models\Recipe;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\PngEncoder;

class ImageExporter implements ExporterInterface
{
    // Global layout and structural configurations
    private const CANVAS_WIDTH = 1080;
    private const CENTER_X = 540;
    private const TIMELINE_START_Y = 810;

    /**
     * Export the coffee recipe into a styled infographic image response.
     */
    public function export(Recipe $recipe): Response
    {
        $manager = new ImageManager(new Driver());
        
        // Eager load steps to optimize query and compute heights
        $recipe->load('recipeSteps');
        $steps = $recipe->recipeSteps ?? [];
        $fontPath = public_path('fonts/poppins.ttf');

        // Dynamically compute layout boundaries
        $totalHeight = $this->calculateTotalHeight($steps);
        $image = $manager->createImage(self::CANVAS_WIDTH, $totalHeight)->fill('#F5EFE6'); 

        // Sequentially render visual building blocks
        $this->renderHeader($image, $manager, $recipe->name, $fontPath);
        $this->renderParameterCard($image, $manager, $recipe, $fontPath);
        $this->renderTimeline($image, $steps, $fontPath);
        $this->renderFooter($image, $totalHeight, $fontPath);

        // Process encoding and dispatch download payload
        $encodedImage = $image->encode(new PngEncoder())->toString();
        $fileName = 'Resep-' . Str::slug($recipe->name) . '.png';

        return response()->streamDownload(function () use ($encodedImage) {
            echo $encodedImage;
        }, $fileName, ['Content-Type' => 'image/png']);
    }

    /**
     * Dynamically compute canvas height based on row metrics and nullable notes.
     */
    private function calculateTotalHeight($steps): int
    {
        $timelineHeight = 0;
        foreach ($steps as $step) {
            $timelineHeight += 80; // Base row offset
            if (!empty($step->note)) {
                $timelineHeight += 45; // Dynamic expansion for additional line
            }
        }

        // Top space baseline + aggregated inner timeline + bottom padding layout
        return self::TIMELINE_START_Y + $timelineHeight + 120;
    }

    /**
     * Render the dark contextual header block and brand title headers.
     */
    private function renderHeader(ImageInterface $image, ImageManager $manager, string $recipeName, string $fontPath): void
    {
        $header = $manager->createImage(self::CANVAS_WIDTH, 380)->fill('#2C1E16');
        $image->insert($header, 0, 0);

        // Adjusted Y position from 160 to 190 to center it vertically perfectly
        $image->text(strtoupper($recipeName), self::CENTER_X, 190, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(70);
            $font->color('#FFFFFF');
            $font->align('center');
        });
    }

    /**
     * Render the structured dashboard card containing multi-row extraction specs.
     */
    private function renderParameterCard(ImageInterface $image, ImageManager $manager, Recipe $recipe, string $fontPath): void
    {
        $card = $manager->createImage(920, 300)->fill('#FFFFFF');
        $image->insert($card, 80, 320);

        // Row 1: Tool & Ingredient identifiers
        $brewerName = $recipe->brewer?->name ?? '-';
        $coffeeName = $recipe->coffee?->name ?? '-';
        $image->text("Alat: {$brewerName}   |   Kopi: {$coffeeName}", self::CENTER_X, 400, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(38);
            $font->color('#333333');
            $font->align('center');
        });

        // Row 2: Environment settings
        $details2 = "Suhu: {$recipe->temperature}°C   |   Grind: " . ucfirst($recipe->grind_size);
        $image->text($details2, self::CENTER_X, 470, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(34);
            $font->color('#666666');
            $font->align('center');
        });

        // Row 3: Yield targets
        $coffeeWeight = $recipe->coffee_weight ?? '-';
        $waterWeight = $recipe->water_weight ?? '-';
        $details3 = "Berat Kopi: {$coffeeWeight}g   |   Total Air: {$waterWeight}ml";
        $image->text($details3, self::CENTER_X, 540, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(34);
            $font->color('#666666');
            $font->align('center');
        });
    }

    /**
     * Render the indexed numeric timeline sequence mapping the extraction intervals.
     */
    private function renderTimeline(ImageInterface $image, $steps, string $fontPath): void
    {
        $image->text('TIMELINE SEDUHAN', self::CENTER_X, 710, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(40);
            $font->color('#2C1E16');
            $font->align('center');
        });

        $yPosition = self::TIMELINE_START_Y;
        $stepNumber = 1;

        foreach ($steps as $step) {
            // Anchor 1: Index Number Marker
            $image->text($stepNumber . '.', 180, $yPosition, function ($font) use ($fontPath) {
                $font->file($fontPath);
                $font->size(36);
                $font->color('#D4A373');
                $font->align('center');
            });

            // Anchor 2: Clock Timestamp
            $timeString = "Detik " . str_pad((string)$step->duration, 2, '0', STR_PAD_LEFT);
            $image->text($timeString, 220, $yPosition, function ($font) use ($fontPath) {
                $font->file($fontPath);
                $font->size(36);
                $font->color('#D4A373');
                $font->align('left');
            });

            // Anchor 3: Action & Volume Payload
            $formattedPourType = ucfirst(str_replace('_', ' ', $step->pour_type));
            $actionText = "Tuang {$step->pour_volume}ml ( {$formattedPourType} )";
            $image->text($actionText, 440, $yPosition, function ($font) use ($fontPath) {
                $font->file($fontPath);
                $font->size(38);
                $font->color('#333333');
                $font->align('left');
            });

            $currentStepHeight = 80;

            // Anchor 4: Extra metadata row (evaluated conditionally)
            if (!empty($step->note)) {
                $image->text($step->note, 440, $yPosition + 40, function ($font) use ($fontPath) {
                    $font->file($fontPath);
                    $font->size(28);          
                    $font->color('#888888');  
                    $font->align('left');
                });
                
                $currentStepHeight += 45; 
            }

            // Push next step row downwards
            $yPosition += $currentStepHeight;
            $stepNumber++; 
        }
    }

    /**
     * Render signature branding credentials localized at baseline coordinates.
     */
    private function renderFooter(ImageInterface $image, int $totalHeight, string $fontPath): void
    {
        $footerY = $totalHeight - 50; 
        $appName = config('app.name', 'Cofee Brewer');
        $image->text("Dibuat dengan {$appName}", self::CENTER_X, $footerY, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(28);
            $font->color('#B0A8A0');
            $font->align('center');
        });
    }
}
