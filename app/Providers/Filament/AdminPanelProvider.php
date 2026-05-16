<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Profile;
use App\Http\Middleware\EnsureFamilyProfileIsComplete;
use App\Filament\Resources\Roles\RoleResource;
use App\Support\PortalContext;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use App\Filament\Widgets\AcolhidoEvolucaoLineChart;
use App\Filament\Widgets\AcolhidosCriadosLineChart;
use App\Filament\Widgets\UsuariosCriadosLineChart;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile(Profile::class, isSimple: false)
            ->databaseNotifications()
            ->databaseNotificationsPolling('10s')
            ->brandName(fn (): string => PortalContext::brandName())
            ->brandLogo(asset('storage/images/logo.png'))
            ->brandLogoHeight(fn (): string => PortalContext::isFamilyUser() ? '52px' : '60px')
            // ->topNavigation((bool) env('FILAMENT_TOPBAR', true))
            ->collapsibleNavigationGroups()
            ->sidebarCollapsibleOnDesktop((bool) env('FILAMENT_COLLAPSEBAR', true))
            ->colors(fn (): array => [
                'primary' => PortalContext::isFamilyUser() ? Color::Rose : Color::Teal,
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): \Illuminate\Contracts\View\View => view('filament.portal.head-theme')
            )
            ->renderHook(
                PanelsRenderHook::SIDEBAR_NAV_START,
                fn (): \Illuminate\Contracts\View\View => view('filament.portal.sidebar-identity')
            )
            ->renderHook(
                PanelsRenderHook::CONTENT_BEFORE,
                fn (): \Illuminate\Contracts\View\View => view('filament.portal.page-banner')
            )
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup('Administracao e Acesso')
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
            ->resources([
                RoleResource::class,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // UsuariosCriadosLineChart::class,
                // AcolhidosCriadosLineChart::class,
                //AcolhidoEvolucaoLineChart::class,
                //  FilamentInfoWidget::class,
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
                EnsureFamilyProfileIsComplete::class,
            ]);
    }
}
