<?php

namespace App\Filament\Resources\GeradorAtividades\Pages;

use App\Filament\Resources\GeradorAtividades\GeradorAtividadeResource;
use App\Support\PortalContext;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewGeradorAtividade extends ViewRecord
{
    protected static string $resource = GeradorAtividadeResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'Quadro semanal de atividades';
    }

    public function getSubheading(): string | Htmlable | null
    {
        $record = $this->getRecord();

        return trim(implode(' | ', array_filter([
            $record->titulo,
            GeradorAtividadeResource::getPeriodLabel($record),
        ])));
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadRelatorio')
                ->label('Baixar PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->hidden(fn (): bool => PortalContext::isFamilyUser())
                ->action(fn () => GeradorAtividadeResource::downloadReportResponse($this->getRecord())),
            EditAction::make(),
        ];
    }
}
