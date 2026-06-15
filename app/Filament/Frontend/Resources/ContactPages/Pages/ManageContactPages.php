<?php

namespace App\Filament\Frontend\Resources\ContactPages\Pages;

use App\Filament\Frontend\Resources\ContactPages\ContactPageResource;
use App\Support\FrontendSchema;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ManageRecords;

class ManageContactPages extends ManageRecords
{
    protected static string $resource = ContactPageResource::class;

    public function mount(): void
    {
        FrontendSchema::ensureContactPagesTableExists();

        parent::mount();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nova pagina'),
        ];
    }

    protected function configureEditAction(EditAction $action): void
    {
        $action->label('Editar pagina');
    }
}
