<?php

namespace App\Filament\Frontend\Pages;

use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationLabel = 'Painel';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?int $navigationSort = -10;

    protected string $view = 'filament.pages.painel-institucional';

    public function getTitle(): string|Htmlable
    {
        return 'Painel institucional';
    }

    public function getWidgets(): array
    {
        return [];
    }
}
