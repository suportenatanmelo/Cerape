<?php

namespace App\Filament\Frontend\Pages;

use App\Models\HeroSlideTrash as Trash;
use App\Models\User;
use Filament\Pages\Page;

class HeroSlideTrash extends Page
{
    protected static ?string $navigationLabel = 'Lixeira do Carrossel';
    protected static string|\UnitEnum|null $navigationGroup = 'Site público';
    protected static ?string $title = 'Lixeira do carrossel';
    protected static ?string $routePath = '/hero-slide-trash';
    protected static bool $shouldRegisterNavigation = false;
    protected string $view = 'filament.pages.hero-slide-trash';

    public function mount(): void
    {
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN]);
    }

    public function getViewData(): array
    {
        return [
            'items' => Trash::query()->orderByDesc('deleted_at')->get(),
        ];
    }
}
