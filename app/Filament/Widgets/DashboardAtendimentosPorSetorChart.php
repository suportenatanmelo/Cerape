<?php

namespace App\Filament\Widgets;

use App\Models\Agenda;
use App\Support\PortalContext;
use Filament\Widgets\ChartWidget;

class DashboardAtendimentosPorSetorChart extends ChartWidget
{
    protected ?string $heading = 'Atendimentos por setor';

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 1];

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $setores = ['Psicologia', 'Serviço social', 'Medico', 'Enfermagem', 'Juridico', 'Terapia Ocupacional', 'Outros'];
        $counts = [];

        foreach ($setores as $setor) {
            $counts[] = Agenda::where('tipo', $setor)->count();
        }

        return [
            'labels' => $setores,
            'datasets' => [[
                'label' => 'Atendimentos',
                'data' => $counts,
                'backgroundColor' => '#2563eb',
            ]],
        ];
    }
}
