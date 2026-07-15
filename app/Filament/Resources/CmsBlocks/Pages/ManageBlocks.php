<?php

namespace App\Filament\Resources\CmsBlocks\Pages;

use App\Filament\Resources\CmsBlocks\BlockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageBlocks extends ManageRecords
{
    protected static string $resource = BlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Novo bloco'),
        ];
    }
}
