<?php

namespace App\Filament\Frontend\Pages;

use App\Models\User;
use Filament\Pages\Page;

class ClearHeroImages extends Page
{
    protected static ?string $navigationLabel = 'Limpar imagens (Hero)';
    protected static string|\UnitEnum|null $navigationGroup = 'Site público';
    protected static ?string $title = 'Limpar imagens do carrossel';
    protected static ?string $routePath = '/clear-hero-images';
    protected static bool $shouldRegisterNavigation = false;
    protected string $view = 'filament.pages.clear-hero-images';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN]);
    }
}
