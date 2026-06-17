<?php

namespace App\Filament\Frontend\Resources\FooterSettings\Pages;

use App\Filament\Frontend\Resources\FooterSettings\FooterSettingResource;
use App\Support\FrontendSchema;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ManageRecords;

class ManageFooterSettings extends ManageRecords
{
    protected static string $resource = FooterSettingResource::class;

    public function mount(): void
    {
        FrontendSchema::ensureFooterSettingsTableExists();
        FrontendSchema::ensureFooterSettingsColumns();

        parent::mount();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Novo rodape'),
        ];
    }

    protected function configureEditAction(EditAction $action): void
    {
        $action->label('Editar rodape');
    }
}
