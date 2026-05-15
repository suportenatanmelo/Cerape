<?php

namespace App\Filament\Resources\AtividadesDesenvolvidas\Pages;

use App\Filament\Resources\AtividadesDesenvolvidas\AtividadeDesenvolvidaResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAtividadeDesenvolvida extends ViewRecord
{
    protected static string $resource = AtividadeDesenvolvidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadRelatorio')
                ->label('Baixar relatorio')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn () => AtividadeDesenvolvidaResource::downloadReportResponse($this->getRecord())),
            EditAction::make(),
        ];
    }
}
