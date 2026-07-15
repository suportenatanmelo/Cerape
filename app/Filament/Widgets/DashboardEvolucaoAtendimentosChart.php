<?php

namespace App\Filament\Widgets;

use App\Models\Agenda;
use App\Support\PortalContext;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DashboardEvolucaoAtendimentosChart extends ChartWidget
{
    protected ?string $heading = 'Evolução dos atendimentos';

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 2];

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $start = now()->subDays(29)->startOfDay();
        $end = now()->endOfDay();

        $values = Agenda::query()
            ->select([DB::raw('DATE(data) as period'), DB::raw('COUNT(*) as total')])
            ->whereBetween('data', [$start, $end])
            ->groupBy('period')
            ->pluck('total', 'period');

        $labels = [];
        $data = [];

        foreach (CarbonPeriod::create($start->copy()->startOfDay(), $end->copy()->startOfDay()) as $date) {
            $key = $date->format('Y-m-d');
            $labels[] = $date->format('d/m');
            $data[] = (int) ($values[$key] ?? 0);
        }

        return [
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Atendimentos',
                'data' => $data,
                'borderColor' => '#0f766e',
                'backgroundColor' => 'rgba(15,118,110,0.15)',
                'fill' => true,
                'tension' => 0.35,
            ]],
        ];
    }
}
