<?php

namespace App\Filament\Resources\DemandasAcolhidos\Pages;

use App\Filament\Resources\DemandasAcolhidos\DemandaAcolhidoResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDemandaAcolhido extends ViewRecord
{
    protected static string $resource = DemandaAcolhidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadRelatorio')
                ->label('Baixar relatorio')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn () => DemandaAcolhidoResource::downloadReportResponse($this->getRecord())),
            EditAction::make(),
        ];
    }
}
