<?php

namespace App\Filament\Resources\AtividadesDesenvolvidas\Pages;

use App\Filament\Resources\AtividadesDesenvolvidas\AtividadeDesenvolvidaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAtividadeDesenvolvida extends ViewRecord
{
    protected static string $resource = AtividadeDesenvolvidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
