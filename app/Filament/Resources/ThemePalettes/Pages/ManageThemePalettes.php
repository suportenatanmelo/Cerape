<?php

namespace App\Filament\Resources\ThemePalettes\Pages;

use App\Filament\Resources\ThemePalettes\ThemePaletteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageThemePalettes extends ManageRecords
{
    protected static string $resource = ThemePaletteResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
