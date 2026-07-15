<?php

namespace App\Providers;

use App\Audit\Observers\AuditObserver;
use App\Audit\Services\AuditService;
use App\Support\ActivityLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

class AuditServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AuditService::class, fn ($app) => new AuditService($app->make(ActivityLogger::class)));
    }

    public function boot(): void
    {
        $models = collect(app('files')->allFiles(app_path('Models')))
            ->map(fn ($file) => 'App\\Models\\' . str_replace(['/', '.php'], ['\\', ''], $file->getRelativePathname()))
            ->filter(fn (string $class) => class_exists($class))
            ->filter(fn (string $class) => (new ReflectionClass($class))->isSubclassOf(Model::class) && ! (new ReflectionClass($class))->isAbstract())
            ->values();

        foreach ($models as $modelClass) {
            $modelClass::observe(AuditObserver::class);
        }
    }
}
