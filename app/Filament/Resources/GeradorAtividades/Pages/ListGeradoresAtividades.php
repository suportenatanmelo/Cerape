<?php

namespace App\Filament\Resources\GeradorAtividades\Pages;

use App\Filament\Resources\GeradorAtividades\GeradorAtividadeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGeradoresAtividades extends ListRecords
{
    protected static string $resource = GeradorAtividadeResource::class;

    public function getTitle(): string
    {
        return 'Gerador de atividades';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Nova semana de atividades'),
        ];
    }
}
