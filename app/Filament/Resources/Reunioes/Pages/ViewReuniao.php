<?php

namespace App\Filament\Resources\Reunioes\Pages;

use App\Filament\Resources\Reunioes\ReuniaoResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewReuniao extends ViewRecord
{
    protected static string $resource = ReuniaoResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'Ata de reunião';
    }

    public function getSubheading(): string | Htmlable | null
    {
        $record = $this->getRecord();

        return trim(implode(' • ', array_filter([
            $record->titulo,
            $record->user?->name ? 'Registrada por ' . $record->user->name : null,
        ])));
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadRelatorio')
                ->label('Baixar PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn () => ReuniaoResource::downloadReportResponse($this->getRecord())),
            EditAction::make(),
        ];
    }
}
