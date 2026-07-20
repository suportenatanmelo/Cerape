<?php

namespace App\Filament\Resources\ThemePalettes\Pages;

use App\Filament\Resources\ThemePalettes\ThemePaletteResource;
use App\Models\ThemePalette;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManageThemePalettes extends ManageRecords
{
    protected static string $resource = ThemePaletteResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }

    public function getViewData(): array
    {
        return [
            'palettes' => ThemePalette::query()->orderBy('position')->get(),
            'activePalette' => ThemePalette::query()->where('is_current', true)->first(),
        ];
    }

    public function applyTheme(int $paletteId): void
    {
        $palette = ThemePalette::query()->findOrFail($paletteId);

        $palette->activate();

        Notification::make()
            ->title('Tema aplicado com sucesso.')
            ->success()
            ->send();
    }
}
