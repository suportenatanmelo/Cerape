<?php

namespace App\Filament\Resources\CmsMedia\Pages;

use App\Filament\Resources\CmsMedia\MediaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMedia extends ManageRecords
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Novo mídia'),
        ];
    }
}
