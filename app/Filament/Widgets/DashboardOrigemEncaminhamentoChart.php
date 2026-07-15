<?php

namespace App\Filament\Widgets;

use App\Models\Acolhido;
use App\Support\PortalContext;
use Filament\Widgets\ChartWidget;

class DashboardOrigemEncaminhamentoChart extends ChartWidget
{
    protected ?string $heading = 'Origem dos encaminhamentos';

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 1];

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        $labels = ['Poder Judiciario', 'Familia', 'CAPS', 'Prefeitura', 'Demanda Espontanea', 'Outros'];
        $data = [];

        foreach ($labels as $label) {
            $data[] = Acolhido::where('meio_de_encaminhamento', 'like', '%'.$label.'%')->count();
        }

        return [
            'labels' => $labels,
            'datasets' => [[
                'data' => $data,
                'backgroundColor' => ['#0f766e', '#2563eb', '#ca8a04', '#dc2626', '#7c3aed', '#16a34a'],
            ]],
        ];
    }
}
