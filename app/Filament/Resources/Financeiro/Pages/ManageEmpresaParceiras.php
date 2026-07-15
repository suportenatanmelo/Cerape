<?php

namespace App\Filament\Resources\Financeiro\Pages;

use App\Filament\Resources\Financeiro\EmpresaParceiraResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEmpresaParceiras extends ManageRecords
{
    protected static string $resource = EmpresaParceiraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Cadastrar empresa parceira'),
        ];
    }
}
