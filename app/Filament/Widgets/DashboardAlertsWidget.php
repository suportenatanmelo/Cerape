<?php

namespace App\Filament\Widgets;

use App\Models\Acolhido;
use App\Models\Agenda;
use App\Support\PortalContext;
use Filament\Widgets\Widget;

class DashboardAlertsWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-alerts';

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 1];

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getViewData(): array
    {
        return [
            'items' => [
                ['label' => 'Medicamentos em falta', 'value' => '0', 'color' => 'danger'],
                ['label' => 'Documentos vencendo', 'value' => Acolhido::whereNull('numero_cpf')->orWhereNull('numero_rg')->count(), 'color' => 'warning'],
                ['label' => 'Consultas de hoje', 'value' => Agenda::whereDate('data', now())->count(), 'color' => 'primary'],
                ['label' => 'Pendências administrativas', 'value' => '0', 'color' => 'gray'],
                ['label' => 'Aniversariantes', 'value' => Acolhido::whereMonth('data_nascimento', now()->month)->whereDay('data_nascimento', now()->day)->count(), 'color' => 'success'],
                ['label' => 'Estoque baixo', 'value' => '0', 'color' => 'danger'],
            ],
        ];
    }
}
