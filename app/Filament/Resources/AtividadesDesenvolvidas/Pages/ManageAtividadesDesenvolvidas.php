<?php

namespace App\Filament\Resources\AtividadesDesenvolvidas\Pages;

use App\Filament\Resources\AtividadesDesenvolvidas\AtividadeDesenvolvidaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAtividadesDesenvolvidas extends ManageRecords
{
    protected static string $resource = AtividadeDesenvolvidaResource::class;

    public function getTitle(): string
    {
        return 'Atividades a serem desenvolvidas';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nova atividade CRC')
                ->after(fn ($record) => AtividadeDesenvolvidaResource::notifyUsers($record, 'created')),
        ];
    }
}
