<?php

namespace App\Filament\Resources\ArquivosDiarios\Pages;

use App\Filament\Resources\ArquivosDiarios\ArquivosDiarioResource;
use App\Support\PortalContext;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewArquivosDiario extends ViewRecord
{
    protected static string $resource = ArquivosDiarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadRelatorio')
                ->label('Baixar relatorio')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->hidden(fn (): bool => PortalContext::isFamilyUser())
                ->action(fn () => ArquivosDiarioResource::downloadReportResponse($this->getRecord())),
            EditAction::make(),
        ];
    }
}
