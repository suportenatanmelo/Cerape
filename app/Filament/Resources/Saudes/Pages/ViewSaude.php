<?php

namespace App\Filament\Resources\Saudes\Pages;

use App\Filament\Resources\Saudes\SaudeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSaude extends ViewRecord
{
    protected static string $resource = SaudeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
