<?php

namespace App\Filament\Resources\Reunioes\Pages;

use App\Filament\Resources\Reunioes\ReuniaoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReunioes extends ListRecords
{
    protected static string $resource = ReuniaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
