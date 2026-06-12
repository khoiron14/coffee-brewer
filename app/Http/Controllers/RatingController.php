<?php

namespace App\Http\Controllers;

use App\Enums\GrindSize;
use App\Enums\PourType;
use App\Http\Controllers\Controller;
use App\Http\Requests\RatingRequest;
use App\Interfaces\BaseServiceInterface;
use App\Models\Brewer;
use App\Models\Coffee;
use App\Models\Rating;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class RatingController extends Controller
{
    public function __construct(
        private BaseServiceInterface $ratingService
    ) {}

    public function show(Rating $rating): Response
    {
        $rating->load(['recipes']);

        return Inertia::render('Rating/Show', [
            'rating' => $rating
        ]);
    }

    public function destroy(Rating $rating): RedirectResponse
    {
        $result = $this->ratingService->delete($rating);

        if ($result instanceof Exception) {
            return back()->withErrors(['error' => 'Gagal menghapus ratnig.']);
        }

        return back()->with('success', 'Rating berhasil dihapus.');
    }
}
