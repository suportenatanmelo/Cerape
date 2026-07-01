<?php

namespace App\Filament\Resources\AtividadesDesenvolvidas\Pages;

use App\Filament\Resources\AtividadesDesenvolvidas\AtividadeDesenvolvidaResource;
use App\Support\PortalContext;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewAtividadeDesenvolvida extends ViewRecord
{
    protected static string $resource = AtividadeDesenvolvidaResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'Plano de atividades CRC';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return $this->getRecord()->acolhido?->nome_completo_paciente;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadRelatorio')
                ->label('Baixar relatorio')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->hidden(fn (): bool => PortalContext::isFamilyUser())
                ->action(fn () => AtividadeDesenvolvidaResource::downloadReportResponse($this->getRecord())),
            EditAction::make(),
        ];
    }
}
