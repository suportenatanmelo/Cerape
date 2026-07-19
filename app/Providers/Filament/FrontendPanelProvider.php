<?php

namespace App\Providers\Filament;

use App\Filament\Frontend\Pages\ClinicSettings;
use App\Filament\Frontend\Pages\ClearHeroImages;
use App\Filament\Frontend\Pages\ContactSettings;
use App\Filament\Frontend\Pages\EditFrontendSettings;
use App\Filament\Frontend\Pages\HeroSlideTrash;
use App\Filament\Frontend\Pages\MediaManager;
use App\Filament\Frontend\Pages\QuemSomos;
use App\Filament\Frontend\Pages\SitePreview;
use App\Filament\Frontend\Pages\WhatsAppSettings;
use App\Filament\Frontend\Resources\BlogPostResource;
use App\Filament\Frontend\Resources\CmsContentResource;
use App\Filament\Frontend\Resources\ContactLeadResource;
use App\Filament\Frontend\Resources\FrontendSettingResource;
use App\Filament\Frontend\Resources\GalleryCategoryResource;
use App\Filament\Frontend\Resources\HeroSlideResource;
use App\Filament\Frontend\Resources\NewsletterSubscriberResource;
use App\Filament\Frontend\Resources\PillarCardResource;
use App\Filament\Frontend\Resources\TeamMemberResource;
use App\Http\Middleware\EnsureFrontendOwnerAccess;
use App\Support\SystemBranding;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
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
            ->brandName(fn (): string => SystemBranding::brandName('CERAPE'))
            ->brandLogo(fn (): ?string => SystemBranding::logoUrl())
            ->colors([
                'primary' => Color::Amber,
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): View => view('filament.partials.branding-head')
            )
            ->resources([
                HeroSlideResource::class,
                FrontendSettingResource::class,
                PillarCardResource::class,
                GalleryCategoryResource::class,
                TeamMemberResource::class,
                BlogPostResource::class,
                CmsContentResource::class,
                NewsletterSubscriberResource::class,
                ContactLeadResource::class,
            ])
            ->pages([
                SitePreview::class,
                EditFrontendSettings::class,
                QuemSomos::class,
                ClinicSettings::class,
                ContactSettings::class,
                WhatsAppSettings::class,
                MediaManager::class,
                ClearHeroImages::class,
                HeroSlideTrash::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup('Administração e acesso')
                    ->navigationSort(100)
                    ->navigationIcon('heroicon-o-shield-check')
                    ->activeNavigationIcon('heroicon-s-shield-check')
                    ->modelLabel('Perfil de acesso')
                    ->pluralModelLabel('Perfis de acesso')
                    ->localizePermissionLabels()
                    ->gridColumns([
                        'default' => 1,
                        'md' => 2,
                        'xl' => 3,
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'md' => 2,
                        'xl' => 3,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'md' => 2,
                    ]),
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
            ])
            ->navigationGroups([
                'Site público',
                'Conteúdo',
                'Mídia',
                'Administração e acesso',
            ]);
    }
}
