<?php

namespace App\Filament\Resources\Saudes\Pages;

use App\Filament\Resources\Saudes\SaudeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSaudes extends ManageRecords
{
    protected static string $resource = SaudeResource::class;

    public function getTitle(): string
    {
        return 'Saude';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nova ficha de saude'),
        ];
    }
}
