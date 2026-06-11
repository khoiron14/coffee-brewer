<?php

namespace App\Providers;

use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CoffeeController;
use App\Interfaces\BaseServiceInterface;
use App\Services\RecipeService;
use App\Services\CoffeeService;
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

        $this->app->when(RecipeController::class)
            ->needs(BaseServiceInterface::class)
            ->give(RecipeService::class);

        $this->app->when(CoffeeController::class)
            ->needs(BaseServiceInterface::class)
            ->give(CoffeeService::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
