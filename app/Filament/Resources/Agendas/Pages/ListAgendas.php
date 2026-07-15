<?php

namespace App\Filament\Resources\Agendas\Pages;

use App\Filament\Resources\Agendas\AgendaResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAgendas extends ListRecords
{
    protected static string $resource = AgendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('calendar')
                ->label('Calendário')
                ->icon('heroicon-o-calendar-days')
                ->url(fn (): string => route('filament.admin.pages.agendas.calendario')),
            CreateAction::make()
                ->label('Novo agendamento'),
        ];
    }
}
