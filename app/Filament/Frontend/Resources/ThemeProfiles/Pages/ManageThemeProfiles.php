<?php

namespace App\Filament\Frontend\Resources\ThemeProfiles\Pages;

use App\Filament\Frontend\Resources\ThemeProfiles\ThemeProfileResource;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ManageRecords;

class ManageThemeProfiles extends ManageRecords
{
    protected static string $resource = ThemeProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Novo perfil'),
        ];
    }

    protected function configureEditAction(EditAction $action): void
    {
        $action->label('Editar perfil');
    }
}
