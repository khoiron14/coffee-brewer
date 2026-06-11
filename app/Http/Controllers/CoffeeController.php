<?php

namespace App\Http\Controllers;

use App\Http\Requests\CoffeeRequest;
use App\Enums\RoastLevel;
use App\Interfaces\BaseServiceInterface;
use App\Models\Coffee;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CoffeeController extends Controller
{
    public function __construct(
        private BaseServiceInterface $coffeeService
    ) {
    }

    public function index(Request $request): Response
    {
        $filters = $request->only([
            'name',
            'roastery',
            'roast_level',
            'per_page',
            'user_id' => Auth::id()
        ]);

        $coffees = $this->coffeeService->getList($filters);
        // dd($coffees);

        return Inertia::render('Coffee/Index', [
            'coffees' => $coffees,
            'filters' => $filters
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Coffee/Create', [
            'roastLevels' => RoastLevel::values()
        ]);
    }

    public function store(CoffeeRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        $result = $this->coffeeService->store($validated);

        if ($result instanceof Exception) {
            return back()->withErrors(['error' => 'Gagal menyimpan kopi: ' . $result->getMessage()]);
        }

        return redirect()->route('coffees.index')->with('success', 'Kopi berhasil disimpan!');
    }

    public function show(Coffee $coffee): Response
    {
        return Inertia::render('Coffee/Show', [
            'coffee' => $coffee
        ]);
    }

    public function edit(Coffee $coffee): Response
    {
        return Inertia::render('Coffee/Edit', [
            'coffee' => $coffee,
            'roastLevels' => RoastLevel::values()
        ]);
    }

    public function update(CoffeeRequest $request, Coffee $coffee): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = $coffee->user_id;

        $result = $this->coffeeService->store($validated, $coffee);

        if ($result instanceof Exception) {
            return back()->withErrors(['error' => 'Gagal memperbarui kopi.']);
        }

        return redirect()->route('coffees.index')->with('success', 'Detail kopi berhasil diperbarui!');
    }

    public function destroy(Coffee $coffee): RedirectResponse
    {
        $result = $this->coffeeService->delete($coffee);

        if ($result instanceof Exception) {
            return back()->withErrors(['error' => 'Gagal menghapus kopi.']);
        }

        return back()->with('success', 'Kopi berhasil dihapus.');
    }
}
