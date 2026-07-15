<?php

namespace App\Filament\Resources\DemandasAcolhidos\Pages;

use App\Filament\Resources\DemandasAcolhidos\DemandaAcolhidoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDemandasAcolhidos extends ListRecords
{
    protected static string $resource = DemandaAcolhidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Nova demanda'),
        ];
    }
}
