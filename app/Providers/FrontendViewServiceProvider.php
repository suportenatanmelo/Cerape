<?php

namespace App\Providers;

use App\Services\Cms\CmsFrontendService;
use App\Models\FrontendSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class FrontendViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // no-op
    }

    public function boot(): void
    {
        View::composer('frontend.*', function ($view) {
            $settings = FrontendSetting::query()->first();

            $cmsService = $this->app->make(CmsFrontendService::class);
            $cmsData = $cmsService->homeData();

            $view->with(array_merge([
                'settings' => $settings,
                'palettes' => collect(),
                'activePalette' => null,
            ], $cmsData));
        });
    }
}
