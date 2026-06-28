<?php

namespace App\Filament\Widgets;

use App\Support\PortalContext;
use Filament\Widgets\Widget;

class DashboardFinanceiroWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-financeiro';

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 1];

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getViewData(): array
    {
        return [
            'receitas' => 'R$ 128.450,00',
            'despesas' => 'R$ 89.230,00',
            'saldo' => 'R$ 39.220,00',
            'contasPagar' => '5 vencendo',
            'contasReceber' => '8 a receber',
        ];
    }
}
