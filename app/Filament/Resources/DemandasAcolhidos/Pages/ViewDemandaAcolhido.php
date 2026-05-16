<?php

namespace App\Filament\Resources\DemandasAcolhidos\Pages;

use App\Filament\Resources\DemandasAcolhidos\DemandaAcolhidoResource;
use App\Support\PortalContext;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewDemandaAcolhido extends ViewRecord
{
    protected static string $resource = DemandaAcolhidoResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'Demanda do acolhido';
    }

    public function getSubheading(): string | Htmlable | null
    {
        $record = $this->getRecord();

        return trim(implode(' • ', array_filter([
            $record->acolhido?->nome_completo_paciente,
            $record->demanda,
        ])));
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadRelatorio')
                ->label('Baixar relatorio')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->hidden(fn (): bool => PortalContext::isFamilyUser())
                ->action(fn () => DemandaAcolhidoResource::downloadReportResponse($this->getRecord())),
            EditAction::make(),
        ];
    }
}
