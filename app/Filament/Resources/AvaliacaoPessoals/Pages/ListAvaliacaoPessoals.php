<?php

namespace App\Filament\Resources\AvaliacaoPessoals\Pages;

use App\Filament\Resources\AvaliacaoPessoals\AvaliacaoPessoalResource;
use App\Filament\Widgets\AvaliacaoPessoalLineChart;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAvaliacaoPessoals extends ListRecords
{
    protected static string $resource = AvaliacaoPessoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nova avaliacao'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AvaliacaoPessoalLineChart::class,
        ];
    }
}
