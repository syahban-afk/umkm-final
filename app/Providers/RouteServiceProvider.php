<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Web routes
        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        // Shop routes
        Route::middleware(['web'])
            ->group(base_path('routes/shop.php'));

        // Admin routes
        Route::middleware(['web'])
            ->prefix('dashboard')
            ->group(base_path('routes/admin.php'));
    }
}
