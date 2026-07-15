<?php

namespace App\Filament\Resources\Financeiro\Pages;

use App\Filament\Resources\Financeiro\FrenteTrabalhoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageFrentesTrabalho extends ManageRecords
{
    protected static string $resource = FrenteTrabalhoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Cadastrar frente de trabalho'),
        ];
    }
}
