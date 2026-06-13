<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // recipe routes
    Route::resource('recipes', \App\Http\Controllers\RecipeController::class);
    Route::resource('coffees', \App\Http\Controllers\CoffeeController::class);

    // rating routes
    Route::get('recipes/{recipe}/ratings/create', [RatingController::class, 'create'])->name('ratings.create');
    Route::post('ratings', [\App\Http\Controllers\RatingController::class, 'store'])->name('ratings.store');
    Route::delete('ratings/{rating}', [\App\Http\Controllers\RatingController::class, 'destroy'])->name('ratings.destroy');
});

require __DIR__.'/auth.php';
