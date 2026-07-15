<?php

namespace App\Filament\Resources\Financeiro\Pages;

use App\Filament\Resources\Financeiro\SaqueFinanceiroResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSaquesFinanceiros extends ManageRecords
{
    protected static string $resource = SaqueFinanceiroResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()->label('Cadastrar saque')]; }
}
