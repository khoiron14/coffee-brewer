<?php

namespace App\Http\Controllers;

use App\Enums\GrindSize;
use App\Enums\PourType;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecipeRequest;
use App\Interfaces\BaseServiceInterface;
use App\Models\Brewer;
use App\Models\Coffee;
use App\Models\Recipe;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class RecipeController extends Controller
{
    public function __construct(
        private BaseServiceInterface $recipeService
    ) {}

    public function index(Request $request): Response
    {
        $filters = $request->only(['name', 'per_page']);
        $recipes = $this->recipeService->getList($filters);

        return Inertia::render('Recipe/Index', [
            'recipes' => $recipes,
            'filters' => $filters
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Recipe/Create', [
            'brewers' => Brewer::select('id', 'name')->get(),
            'coffees' => Coffee::select('id', 'name')->get(),
            'grindSizes' => GrindSize::values(),
            'pourTypes' => PourType::values(),
        ]);
    }

    public function store(RecipeRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        $result = $this->recipeService->store($validated);

        if ($result instanceof Exception) {
            return back()->withErrors(['error' => 'Gagal menyimpan resep: ' . $result->getMessage()]);
        }

        return redirect()->route('recipes.index')->with('success', 'Resep seduh berhasil disimpan!');
    }

    public function show(Recipe $recipe): Response
    {
        $recipe->load(['recipeSteps', 'brewer', 'coffee']);

        return Inertia::render('Recipe/Show', [
            'recipe' => $recipe
        ]);
    }


    public function edit(Recipe $recipe): Response
    {
        $recipe->load('recipeSteps', 'brewer:id,name', 'coffee:id,name');

        return Inertia::render('Recipe/Edit', [
            'recipe' => $recipe,
            'brewers' => Brewer::select('id', 'name')->get(),
            'coffees' => Coffee::select('id', 'name')->get(),
            'grindSizes' => GrindSize::values(),
            'pourTypes' => PourType::values(),
        ]);
    }

    public function update(RecipeRequest $request, Recipe $recipe): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = $recipe->user_id;

        $result = $this->recipeService->store($validated, $recipe);

        if ($result instanceof Exception) {
            return back()->withErrors(['error' => 'Gagal memperbarui resep.']);
        }

        return redirect()->route('recipes.index')->with('success', 'Detail resep berhasil diperbarui!');
    }

    public function destroy(Recipe $recipe): RedirectResponse
    {
        $result = $this->recipeService->delete($recipe);

        if ($result instanceof Exception) {
            return back()->withErrors(['error' => 'Gagal menghapus resep.']);
        }

        return back()->with('success', 'Resep berhasil dihapus.');
    }
}
