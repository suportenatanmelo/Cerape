<?php

namespace App\Filament\Resources\Homes\Pages;

use App\Filament\Resources\Homes\HomeResource;
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
                ->label('Novo conteúdo'),
        ];
    }

    protected function configureEditAction(EditAction $action): void
    {
        $action->label('Editar conteúdo');
    }
}
