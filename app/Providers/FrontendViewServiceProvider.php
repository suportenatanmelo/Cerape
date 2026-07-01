<?php

namespace App\Providers;

use App\Services\Cms\CmsFrontendService;
use App\Models\FrontendSetting;
use App\Models\ThemePalette;
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
            $palettes = ThemePalette::query()->where('is_active', true)->orderBy('position')->limit(50)->get();
            $activePalette = ThemePalette::query()->where('is_current', true)->first();

            $cmsService = $this->app->make(CmsFrontendService::class);
            $cmsData = $cmsService->homeData();

            $view->with(array_merge([
                'settings' => $settings,
                'palettes' => $palettes,
                'activePalette' => $activePalette,
            ], $cmsData));
        });
    }
}
