<?php

namespace App\Http\Controllers;

use App\Enums\GrindSize;
use App\Enums\PourType;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecipeRequest;
use App\Interfaces\BaseServiceInterface;
use App\Models\Recipe;
use App\Models\Brewer;
use App\Models\Coffee;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashbaordController extends Controller
{
    public function __construct(
        private BaseServiceInterface $recipeService
    ) {}

    public function index(): Response
    {
        $publicRecipes = $this->recipeService->getList([
            'is_published' => true,
            'per_page' => 6,
        ]);

        $ownRecipes = $this->recipeService->getList([
            'user_id' => Auth::id(),
            'per_page' => 6,
        ]);

        return Inertia::render('Dashboard', [
            // 'publicRecipes' => $publicRecipes,
            // 'ownRecipes' => $ownRecipes,
        ]);
    }
}
