<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class PainelInstitucional extends Dashboard
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string $routePath = '/painel-institucional';

    protected static ?string $navigationLabel = 'Painel';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?int $navigationSort = -10;

    protected string $view = 'filament.pages.painel-institucional';

    public function getTitle(): string|Htmlable
    {
        return 'Painel CERAPE';
    }

    public function getWidgets(): array
    {
        return [];
    }
}
