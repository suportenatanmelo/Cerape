<?php

namespace App\Filament\Widgets;

use App\Models\Acolhido;
use App\Support\PortalContext;
use Filament\Widgets\ChartWidget;

class DashboardSituacaoAcolhidosChart extends ChartWidget
{
    protected ?string $heading = 'Situação dos acolhidos';

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 1];

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        return [
            'labels' => ['Em tratamento', 'Alta', 'Transferidos', 'Desistentes'],
            'datasets' => [[
                'data' => [
                    Acolhido::where('ativo', true)->count(),
                    Acolhido::where('ativo', false)->count(),
                    0,
                    0,
                ],
                'backgroundColor' => ['#0f766e', '#2563eb', '#ca8a04', '#dc2626'],
                'borderColor' => '#ffffff',
                'borderWidth' => 2,
            ]],
        ];
    }
}
