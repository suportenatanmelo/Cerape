<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ManageRecords;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Criar usuario')
                ->using(fn (array $data) => UserResource::createUserWithRoles($data)),
        ];
    }

    protected function configureEditAction(EditAction $action): void
    {
        $action->using(fn ($record, array $data) => UserResource::updateUserWithRoles($record, $data));
    }
}
