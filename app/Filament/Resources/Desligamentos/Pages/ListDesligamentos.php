<?php

namespace App\Filament\Resources\Desligamentos\Pages;

use App\Filament\Resources\Desligamentos\DesligamentoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDesligamentos extends ListRecords
{
    protected static string $resource = DesligamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
