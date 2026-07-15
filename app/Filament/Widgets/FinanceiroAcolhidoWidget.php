<?php

namespace App\Filament\Widgets;

use App\Models\Acolhido;
use Filament\Widgets\Widget;

class FinanceiroAcolhidoWidget extends Widget
{
    protected string $view = 'filament.widgets.financeiro-acolhido';

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 1];

    public static function canView(): bool
    {
        return ! app()->runningInConsole();
    }

    protected function getViewData(): array
    {
        $acolhidos = Acolhido::query()->orderBy('nome_completo_paciente')->limit(200)->get(['id', 'nome_completo_paciente']);

        return [
            'acolhidos' => $acolhidos,
            'extratoRoute' => '/admin/extrato-financeiro',
        ];
    }
}
