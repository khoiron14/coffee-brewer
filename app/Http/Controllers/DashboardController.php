<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\BaseServiceInterface;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
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
        $publicRecipes->getCollection()->load('ratings', 'user', 'brewer', 'coffee');

        $ownRecipes = $this->recipeService->getList([
            'user_id' => Auth::id(),
            'per_page' => 6,
        ]);
        $ownRecipes->getCollection()->load('ratings', 'brewer', 'coffee');

        return Inertia::render('Dashboard', [
            'publicRecipes' => $publicRecipes,
            'ownRecipes' => $ownRecipes,
        ]);
    }
}
