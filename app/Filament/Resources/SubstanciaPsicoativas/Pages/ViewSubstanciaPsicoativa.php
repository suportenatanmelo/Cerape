<?php

namespace App\Filament\Resources\SubstanciaPsicoativas\Pages;

use App\Filament\Resources\SubstanciaPsicoativas\SubstanciaPsicoativaResource;
use App\Support\PortalContext;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewSubstanciaPsicoativa extends ViewRecord
{
    protected static string $resource = SubstanciaPsicoativaResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'Historico de substancias';
    }

    public function getSubheading(): string | Htmlable | null
    {
        $record = $this->getRecord();

        return trim(implode(' • ', array_filter([
            $record->acolhido?->nome_completo_paciente,
            is_string($record->nome) ? $record->nome : null,
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
                ->action(fn () => SubstanciaPsicoativaResource::downloadReportResponse($this->getRecord())),
            EditAction::make(),
        ];
    }
}
