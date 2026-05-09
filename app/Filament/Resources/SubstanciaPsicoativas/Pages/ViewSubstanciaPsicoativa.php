<?php

namespace App\Filament\Resources\SubstanciaPsicoativas\Pages;

use App\Filament\Resources\SubstanciaPsicoativas\SubstanciaPsicoativaResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSubstanciaPsicoativa extends ViewRecord
{
    protected static string $resource = SubstanciaPsicoativaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadRelatorio')
                ->label('Baixar relatorio')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn () => SubstanciaPsicoativaResource::downloadReportResponse($this->getRecord())),
            EditAction::make(),
        ];
    }
}
