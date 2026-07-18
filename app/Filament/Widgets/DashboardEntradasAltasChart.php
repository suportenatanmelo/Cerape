<?php

namespace App\Filament\Widgets;

use App\Models\Acolhido;
use App\Support\PortalContext;
use Carbon\CarbonPeriod;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;
use App\Support\Db as DbSupport;

class DashboardEntradasAltasChart extends LineChartWidget
{
    protected ?string $heading = 'Entradas x Altas';

    protected ?string $description = 'Últimos 12 meses';

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 2];

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getData(): array
    {
        $start = now()->subMonths(11)->startOfMonth();
        $end = now()->endOfMonth();

        $entradas = DbSupport::safe(function () use ($start, $end) {
            return Acolhido::query()
                ->select([DB::raw("DATE_FORMAT(created_at, '%Y-%m') as period"), DB::raw('COUNT(*) as total')])
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('period')
                ->pluck('total', 'period');
        }, collect());

        $altas = DbSupport::safe(function () use ($start, $end) {
            return Acolhido::query()
                ->select([DB::raw("DATE_FORMAT(updated_at, '%Y-%m') as period"), DB::raw('COUNT(*) as total')])
                ->where('ativo', false)
                ->whereBetween('updated_at', [$start, $end])
                ->groupBy('period')
                ->pluck('total', 'period');
        }, collect());

        $labels = [];
        $entradasValues = [];
        $altasValues = [];

        foreach (CarbonPeriod::create($start->copy()->startOfMonth(), '1 month', $end->copy()->startOfMonth()) as $date) {
            $key = $date->format('Y-m');
            $labels[] = $date->translatedFormat('M/Y');
            $entradasValues[] = (int) ($entradas[$key] ?? 0);
            $altasValues[] = (int) ($altas[$key] ?? 0);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                ['label' => 'Entradas', 'data' => $entradasValues, 'borderColor' => '#0f766e', 'backgroundColor' => 'rgba(15,118,110,0.15)', 'tension' => 0.35],
                ['label' => 'Altas', 'data' => $altasValues, 'borderColor' => '#dc2626', 'backgroundColor' => 'rgba(220,38,38,0.15)', 'tension' => 0.35],
            ],
        ];
    }
}
