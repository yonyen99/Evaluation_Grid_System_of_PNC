<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Use Bootstrap 5 styles for pagination globally
        Paginator::useBootstrapFive();

        // If your project uses Bootstrap 4, uncomment this instead:
        // Paginator::useBootstrapFour();
    }

}
