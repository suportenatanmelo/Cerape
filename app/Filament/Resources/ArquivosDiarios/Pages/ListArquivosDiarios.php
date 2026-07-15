<?php

namespace App\Filament\Resources\ArquivosDiarios\Pages;

use App\Filament\Resources\ArquivosDiarios\ArquivosDiarioResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListArquivosDiarios extends ListRecords
{
    protected static string $resource = ArquivosDiarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
