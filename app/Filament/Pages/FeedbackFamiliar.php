<?php

namespace App\Filament\Pages;

use App\Models\ChMessage;
use App\Models\User;
use App\Support\PortalContext;
use App\Support\ShieldPermission;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Page;
use Illuminate\Http\RedirectResponse;

class FeedbackFamiliar extends Page
{
    protected static ?string $navigationParentItem = 'Chat';

    protected static ?string $navigationLabel = 'Chat familiar';

    protected static ?string $title = 'Chat familiar';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.feedback-familiar';

    public function mount(): RedirectResponse|null
    {
        if (! auth()->check()) {
            return null;
        }

        return redirect(static::getChatRoute());
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User
            && ShieldPermission::allows($user, 'view', 'FeedbackFamiliar');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return PortalContext::communicationNavigationGroup();
    }

    public static function getNavigationBadge(): ?string
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return null;
        }

        $count = ChMessage::query()
            ->where('to_id', $user->getKey())
            ->where('seen', 0)
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Mensagens aguardando leitura';
    }

    public static function getNavigationUrl(): string
    {
        return static::getChatRoute();
    }

    /**
     * @return array<NavigationItem>
     */
    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make(static::getNavigationLabel())
                ->group(static::getNavigationGroup())
                ->parentItem(static::getNavigationParentItem())
                ->isActiveWhen(fn (): bool => request()->routeIs(static::getRouteName()) || request()->is('chatify*'))
                ->sort(static::getNavigationSort())
                ->badge(static::getNavigationBadge(), color: static::getNavigationBadgeColor())
                ->badgeTooltip(static::getNavigationBadgeTooltip())
                ->url(static::getNavigationUrl()),
        ];
    }

    protected static function getChatRoute(): string
    {
        return route(config('chatify.routes.prefix'));
    }
}
