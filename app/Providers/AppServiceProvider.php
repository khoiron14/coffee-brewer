<?php

namespace App\Providers;

use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CoffeeController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\DashboardController;
use App\Interfaces\BaseServiceInterface;
use App\Services\RecipeService;
use App\Services\CoffeeService;
use App\Services\RatingService;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register singleton services
        $this->app->singleton(RecipeService::class, RecipeService::class);
        $this->app->singleton(CoffeeService::class, CoffeeService::class);
        $this->app->singleton(RatingService::class, RatingService::class);

        $this->app->when(RecipeController::class)
            ->needs(BaseServiceInterface::class)
            ->give(RecipeService::class);

        $this->app->when(CoffeeController::class)
            ->needs(BaseServiceInterface::class)
            ->give(CoffeeService::class);

        $this->app->when(RatingController::class)
            ->needs(BaseServiceInterface::class)
            ->give(RatingService::class);

        $this->app->when(DashboardController::class)
            ->needs(BaseServiceInterface::class)
            ->give(RecipeService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
