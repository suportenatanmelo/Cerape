<?php

namespace App\Filament\Resources\DemandasAcolhidos\Pages;

use App\Filament\Resources\DemandasAcolhidos\DemandaAcolhidoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDemandasAcolhidos extends ManageRecords
{
    protected static string $resource = DemandaAcolhidoResource::class;

    public function getTitle(): string
    {
        return 'Demanda do acolhido';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nova demanda')
                ->after(fn ($record) => DemandaAcolhidoResource::notifyUsers($record, 'created')),
        ];
    }
}
