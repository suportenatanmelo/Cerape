<?php

namespace App\Providers\Filament;

use Alsaloul\ImageGallery\ImageGalleryPlugin;
use Awcodes\Curator\CuratorPlugin;
use App\Filament\Pages\FeedbackFamiliar;
use App\Filament\Pages\Profile;
use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Widgets\AcolhidoEvolucaoLineChart;
use App\Filament\Widgets\AcolhidosCriadosLineChart;
use App\Filament\Widgets\AvaliacaoPessoalLineChart;
use App\Filament\Widgets\DemandasAcolhidosLineChart;
use App\Filament\Widgets\UsuariosCriadosLineChart;
use App\Filament\Widgets\UsuariosVinculadosAcolhidoLineChart;
use App\Http\Middleware\EnsureFamilyProfileIsComplete;
use App\Support\PortalContext;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
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
            ]
            : [
                PortalContext::portalNavigationGroup(),
                PortalContext::evaluationNavigationGroup(),
                PortalContext::documentsNavigationGroup(),
                PortalContext::mediaNavigationGroup(),
                PortalContext::communicationNavigationGroup(),
                'Administracao e Acesso',
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
                NavigationItem::make('GOV BR')
                    ->url('https://servicos.acesso.gov.br/')
                    ->icon('heroicon-o-globe-alt')
                    ->sort(99),
            ])
            ->profile(Profile::class, isSimple: false)
            ->databaseNotifications()
            ->databaseNotificationsPolling('10s')
            ->brandName(fn (): string => PortalContext::brandName())
            ->brandLogo(asset('storage/images/logo.png'))
            ->brandLogoHeight(fn (): string => PortalContext::isFamilyUser() ? '52px' : '60px')
            ->homeUrl(fn (): ?string => PortalContext::familyDashboardUrl())
            // ->topNavigation((bool) env('FILAMENT_TOPBAR', true))
            ->collapsibleNavigationGroups()
            ->sidebarCollapsibleOnDesktop((bool) env('FILAMENT_COLLAPSEBAR', true))
            ->colors(fn (): array => [
                'primary' => PortalContext::isFamilyUser() ? Color::Rose : Color::Teal,
            ])
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
            ->renderHook(
                PanelsRenderHook::FOOTER,
                fn (): View => view('filament.portal.footer')
            )
            ->plugins([
                ImageGalleryPlugin::make(),
                CuratorPlugin::make()
                    ->label('Galeria Familiar')
                    ->pluralLabel('Galeria Familiar')
                    ->navigationIcon('heroicon-o-photo')
                    ->navigationGroup(fn (): string => PortalContext::mediaNavigationGroup())
                    ->navigationSort(3)
                    ->registerNavigation(fn (): bool => PortalContext::isFamilyUser()),
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
                UsuariosCriadosLineChart::class,
                UsuariosVinculadosAcolhidoLineChart::class,
                AcolhidosCriadosLineChart::class,
                DemandasAcolhidosLineChart::class,
                AvaliacaoPessoalLineChart::class,
                AcolhidoEvolucaoLineChart::class,
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
