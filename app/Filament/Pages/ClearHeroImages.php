<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Support\PortalContext;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Page;

class ClearHeroImages extends Page
{
    protected static ?string $navigationLabel = 'Limpar imagens (Hero)';
    protected static string|\UnitEnum|null $navigationGroup = 'Administração e acesso';
    protected static ?int $navigationSort = 120;
    protected static string $routePath = '/clear-hero-images';
    protected string $view = 'filament.pages.clear-hero-images';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN]);
    }

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make(static::$navigationLabel)
                ->group(static::$navigationGroup)
                ->url(static::getUrl())
                ->sort(static::$navigationSort),
        ];
    }
}
