<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RatingRequest;
use App\Interfaces\BaseServiceInterface;
use App\Models\Rating;
use App\Models\Recipe;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class RatingController extends Controller
{
    public function __construct(
        private BaseServiceInterface $ratingService
    ) {}

    public function store(RatingRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        $result = $this->ratingService->store($validated);

        if ($result instanceof Exception) {
            return back()->withErrors(['error' => 'Gagal menyimpan rating: ' . $result->getMessage()]);
        }

        return redirect()->route('recipes.show', $validated['recipe_id'])
            ->with('success', 'Rating berhasil disimpan!');
    }


    public function destroy(Rating $rating): RedirectResponse
    {
        $result = $this->ratingService->delete($rating);

        if ($result instanceof Exception) {
            return back()->withErrors(['error' => 'Gagal menghapus rating.']);
        }

        return back()->with('success', 'Rating berhasil dihapus.');
    }
}
