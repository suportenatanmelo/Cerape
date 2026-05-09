<?php

namespace App\Providers;

use App\Models\Acolhido;
use App\Observers\AcolhidoObserver;
use Illuminate\Support\ServiceProvider;

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
    public function boot(): void
    {
        Acolhido::observe(AcolhidoObserver::class);
    }
}
