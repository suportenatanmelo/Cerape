<?php

namespace App\Filament\Resources\Financeiro\Pages;

use App\Filament\Resources\Financeiro\DiariaTrabalhoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDiariasTrabalho extends ManageRecords
{
    protected static string $resource = DiariaTrabalhoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Cadastrar diária'),
        ];
    }
}
