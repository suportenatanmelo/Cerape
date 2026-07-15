<?php

namespace App\Providers;

use App\Observers\ActivityLogObserver;
use App\Services\AuditService;
use App\Support\ActivityLogger;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use ReflectionClass;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Router;

class ActivityLogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ActivityLogger::class);

        $this->app->singleton(AuditService::class, function ($app): AuditService {
            return new AuditService($app->make(ActivityLogger::class));
        });
    }

    public function boot(ActivityLogger $logger): void
    {
        $this->registerModelObservers();
        $this->registerAuthListeners($logger);

        // Alias middleware so it can be used in routes or globally via kernel
        if ($this->app->bound(Router::class)) {
            $this->app->make(Router::class)->aliasMiddleware('audit.access', \App\Audit\Middleware\AuditMiddleware::class);
        }
    }

    private function registerModelObservers(): void
    {
        $models = File::exists(app_path('Models'))
            ? collect(File::allFiles(app_path('Models')))
                ->map(function ($file): string {
                    $relative = Str::of($file->getRealPath())
                        ->after(app_path() . DIRECTORY_SEPARATOR)
                        ->beforeLast('.php')
                        ->replace(DIRECTORY_SEPARATOR, '\\')
                        ->toString();

                    return 'App\\' . $relative;
                })
                ->filter(fn (string $class): bool => class_exists($class))
                ->filter(fn (string $class): bool => (new ReflectionClass($class))->isSubclassOf(Model::class) && ! (new ReflectionClass($class))->isAbstract())
                ->values()
                ->all()
            : [];

        $models = array_merge($models, [
            Role::class,
            Permission::class,
        ]);

        foreach (array_unique($models) as $modelClass) {
            if (! is_string($modelClass) || ! class_exists($modelClass) || ! is_subclass_of($modelClass, Model::class)) {
                continue;
            }

            $modelClass::observe(ActivityLogObserver::class);
        }
    }

    private function registerAuthListeners(ActivityLogger $logger): void
    {
        Event::listen(Login::class, static function (Login $event) use ($logger): void {
            $logger->login($event->user);
        });

        Event::listen(Logout::class, static function (Logout $event) use ($logger): void {
            $logger->logout($event->user ?? null);
        });

        Event::listen(Failed::class, static function (Failed $event) use ($logger): void {
            $logger->failedLogin((array) $event->credentials);
        });
    }
}
