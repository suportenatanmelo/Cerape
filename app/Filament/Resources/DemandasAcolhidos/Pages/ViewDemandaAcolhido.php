<?php

namespace App\Filament\Resources\DemandasAcolhidos\Pages;

use App\Filament\Resources\DemandasAcolhidos\DemandaAcolhidoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDemandaAcolhido extends ViewRecord
{
    protected static string $resource = DemandaAcolhidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
