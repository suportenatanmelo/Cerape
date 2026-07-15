<?php

namespace App\Filament\Pages;

use App\Models\HeroSlideTrash as Trash;
use App\Models\User;
use Filament\Pages\Page;

class HeroSlideTrash extends Page
{
    protected static ?string $navigationLabel = 'Lixeira do Carrossel';
    protected static bool $shouldRegisterNavigation = false;
    protected static string|\UnitEnum|null $navigationGroup = 'Administração e acesso';
    protected static ?int $navigationSort = 121;
    protected static string $routePath = '/hero-slide-trash';
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
