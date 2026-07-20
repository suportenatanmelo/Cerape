<?php

namespace App\Providers\Filament;

use Alsaloul\ImageGallery\ImageGalleryPlugin;
use App\Filament\Pages\Agenda;
use App\Filament\Pages\BrandSettings;
use App\Filament\Pages\Dashboard as AdminDashboard;
use App\Filament\Pages\FeedbackFamiliar;
use App\Filament\Pages\Profile;
use App\Filament\Resources\ActivityLogs\ActivityLogResource;
use App\Http\Middleware\EnsureFamilyProfileIsComplete;
use App\Support\PortalContext;
use App\Support\SystemBranding;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
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
        $navigationGroups = PortalContext::isFamilyUser()
            ? [
                PortalContext::portalNavigationGroup(),
                PortalContext::communicationNavigationGroup(),
            ]
            : [
                PortalContext::portalNavigationGroup(),
                PortalContext::evaluationNavigationGroup(),
                PortalContext::documentsNavigationGroup(),
                PortalContext::mediaNavigationGroup(),
                PortalContext::communicationNavigationGroup(),
                'Administração e acesso',
            ];

        $defaultColors = [
            'primary' => '#0f766e',
            'secondary' => '#155e75',
            'accent' => '#38bdf8',
            'success' => '#16a34a',
            'warning' => '#f59e0b',
            'danger' => '#dc2626',
            'info' => '#0284c7',
        ];

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->topNavigation(false)
            ->navigationGroups($navigationGroups)
            ->navigationItems([
                NavigationItem::make('Chat')
                    ->group(fn (): string => PortalContext::communicationNavigationGroup())
                    ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                    ->isActiveWhen(fn (): bool => request()->is('chatify*') || request()->routeIs(FeedbackFamiliar::getRouteName()))
                    ->sort(1)
                    ->url(fn (): string => route(config('chatify.routes.prefix')))
                    ->visible(fn (): bool => FeedbackFamiliar::canAccess()),
                NavigationItem::make('Auditoria')
                    ->group(fn (): string => 'Administração e acesso')
                    ->icon(Heroicon::OutlinedClipboardDocumentList)
                    ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.activity-logs.*'))
                    ->sort(98)
                    ->url(fn (): string => url('/admin/activity-logs'))
                    ->visible(fn (): bool => ActivityLogResource::canAccess()),

            ])
            ->profile(Profile::class, isSimple: false)
            ->userMenuItems([
                MenuItem::make()
                    ->label('Identidade visual')
                    ->icon(Heroicon::OutlinedSwatch)
                    ->url(fn (): string => BrandSettings::getUrl())
                    ->sort(PHP_INT_MAX - 1)
                    ->visible(fn (): bool => BrandSettings::canAccess()),
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling('10s')
            ->brandName(fn (): string => PortalContext::brandName())
            ->brandLogo(fn (): ?string => SystemBranding::logoUrl())
            ->brandLogoHeight(fn (): string => PortalContext::isFamilyUser() ? '52px' : '60px')
            ->homeUrl(fn (): ?string => PortalContext::familyDashboardUrl())
            //->topNavigation((bool) env('FILAMENT_TOPBAR', true))
            ->collapsibleNavigationGroups()
            ->sidebarCollapsibleOnDesktop((bool) env('FILAMENT_COLLAPSEBAR', true))
            ->colors(fn (): array => $defaultColors)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): View => view('filament.portal.head-theme')
            )
            ->renderHook(
                PanelsRenderHook::SIDEBAR_NAV_START,
                fn (): View => view('filament.portal.sidebar-identity')
            )
            ->renderHook(
                PanelsRenderHook::CONTENT_BEFORE,
                fn (): View => view('filament.portal.page-banner')
            )
            ->renderHook(
                PanelsRenderHook::BODY_START,
                fn (): View => view('filament.portal.family-dashboard-url')
            )
            ->plugins([
                ImageGalleryPlugin::make(),
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
            ->resources([])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                AdminDashboard::class,
                Agenda::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
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
