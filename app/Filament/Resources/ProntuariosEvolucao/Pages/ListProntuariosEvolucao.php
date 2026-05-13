<?php

namespace App\Filament\Resources\ProntuariosEvolucao\Pages;

use App\Filament\Resources\ProntuariosEvolucao\ProntuarioEvolucaoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProntuariosEvolucao extends ListRecords
{
    protected static string $resource = ProntuarioEvolucaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Novo prontuario de evolucao'),
        ];
    }
}
