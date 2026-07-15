<?php

namespace App\Providers;

use App\Services\AuditService;
use App\Observers\AuditObserver;
use Illuminate\Support\ServiceProvider;

class AuditServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register the observer for models that use the Auditable trait
        $this->registerObservers();
    }

    /**
     * Register the application's services.
     *
     * @return void
     */
    public function register()
    {
        // Bind the AuditService to the service container
        $this->app->singleton(AuditService::class, function ($app) {
            return new AuditService();
        });
    }

    /**
     * Register the observers for the models.
     *
     * @return void
     */
    protected function registerObservers()
    {
        // Register the AuditObserver for models that use the Auditable trait
        // Example: \App\Models\YourModel::observe(AuditObserver::class);
    }
}