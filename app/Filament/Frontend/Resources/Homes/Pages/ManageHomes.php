<?php

namespace App\Filament\Frontend\Resources\Homes\Pages;

use App\Filament\Frontend\Resources\Homes\HomeResource;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ManageRecords;

class ManageHomes extends ManageRecords
{
    protected static string $resource = HomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nova imagem'),
        ];
    }

    protected function configureEditAction(EditAction $action): void
    {
        $action->label('Editar imagem');
    }
}
