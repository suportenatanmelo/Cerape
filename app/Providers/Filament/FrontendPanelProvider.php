<?php

namespace App\Providers\Filament;

use App\Filament\Frontend\Resources\BlogPostResource;
use App\Filament\Frontend\Resources\ContactLeadResource;
use App\Filament\Frontend\Resources\HeroSlideResource;
use App\Filament\Frontend\Resources\FrontendSettingResource;
use App\Filament\Frontend\Resources\GalleryCategoryResource;
use App\Filament\Frontend\Resources\PillarCardResource;
use App\Filament\Frontend\Resources\TeamMemberResource;
use App\Filament\Frontend\Pages\MediaManager;
use App\Filament\Frontend\Pages\QuemSomos;
use App\Filament\Frontend\Pages\SitePreview;
use App\Http\Middleware\EnsureFrontendOwnerAccess;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class FrontendPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('frontend')
            ->path('frontend')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->resources([
                HeroSlideResource::class,
                FrontendSettingResource::class,
                PillarCardResource::class,
                GalleryCategoryResource::class,
                TeamMemberResource::class,
                BlogPostResource::class,
                ContactLeadResource::class,
            ])
            ->pages([
                SitePreview::class,
                QuemSomos::class,
                MediaManager::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                EnsureFrontendOwnerAccess::class,
            ]);
    }
}
