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
            Action::make('visualizarArquivo')
                ->label('Visualizar PDF')
                ->icon('heroicon-o-eye')
                ->color('primary')
                ->hidden(fn (): bool => PortalContext::isFamilyUser())
                ->visible(fn (): bool => filled($this->getRecord()->upload_arquivo))
                ->url(fn () => route('arquivos-upload.preview', $this->getRecord()), shouldOpenInNewTab: true),
            Action::make('baixarArquivo')
                ->label('Baixar arquivo')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->hidden(fn (): bool => PortalContext::isFamilyUser())
                ->visible(fn (): bool => filled($this->getRecord()->upload_arquivo))
                ->url(fn () => route('arquivos-upload.download', $this->getRecord()), shouldOpenInNewTab: true),
            Action::make('downloadArquivo')
                ->label('Baixar arquivo')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->hidden(fn (): bool => PortalContext::isFamilyUser())
                ->action(fn () => ArquivosDiarioResource::downloadReportResponse($this->getRecord())),
            EditAction::make(),
        ];
    }
}
