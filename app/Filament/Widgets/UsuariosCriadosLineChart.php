<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;

class UsuariosCriadosLineChart extends LineChartWidget
{
    protected static bool $isDiscovered = false;

    protected ?string $heading = 'Cadastros de usuarios';

    protected ?string $description = 'Quantidade de usuarios cadastrados por periodo.';

    protected ?string $maxHeight = '320px';

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'xl' => 1,
    ];

    public ?string $filter = 'semanal';

    protected function getFilters(): ?array
    {
        return [
            'semanal' => 'Semanal',
            'mensal' => 'Mensal',
            'semestral' => 'Semestral',
            'anual' => 'Anual',
        ];
    }

    protected function getData(): array
    {
        [$labels, $values] = $this->getSeries();

        return [
            'datasets' => [
                [
                    'label' => 'Usuarios cadastrados',
                    'data' => $values,
                    'borderColor' => '#0f766e',
                    'backgroundColor' => 'rgba(15, 118, 110, 0.12)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
        ];
    }

    /**
     * @return array{0: array<int, string>, 1: array<int, int>}
     */
    private function getSeries(): array
    {
        return match ($this->filter) {
            'mensal' => $this->dailySeries(now()->startOfMonth(), now()->endOfMonth(), 'd/m'),
            'semestral' => $this->monthlySeries(now()->subMonths(5)->startOfMonth(), now()->endOfMonth()),
            'anual' => $this->monthlySeries(now()->subMonths(11)->startOfMonth(), now()->endOfMonth()),
            default => $this->dailySeries(now()->subDays(6)->startOfDay(), now()->endOfDay(), 'd/m'),
        };
    }

    /**
     * @return array{0: array<int, string>, 1: array<int, int>}
     */
    private function dailySeries(Carbon $start, Carbon $end, string $labelFormat): array
    {
        $totals = User::query()
            ->selectRaw('DATE(created_at) as period, COUNT(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('period')
            ->pluck('total', 'period');

        $labels = [];
        $values = [];

        foreach (CarbonPeriod::create($start->copy()->startOfDay(), $end->copy()->startOfDay()) as $date) {
            $key = $date->format('Y-m-d');

            $labels[] = $date->format($labelFormat);
            $values[] = (int) ($totals[$key] ?? 0);
        }

        return [$labels, $values];
    }

    /**
     * @return array{0: array<int, string>, 1: array<int, int>}
     */
    private function monthlySeries(Carbon $start, Carbon $end): array
    {
        $totals = User::query()
            ->select([
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as period"),
                DB::raw('COUNT(*) as total'),
            ])
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('period')
            ->pluck('total', 'period');

        $labels = [];
        $values = [];

        foreach (CarbonPeriod::create($start->copy()->startOfMonth(), '1 month', $end->copy()->startOfMonth()) as $date) {
            $key = $date->format('Y-m');

            $labels[] = $date->translatedFormat('M/Y');
            $values[] = (int) ($totals[$key] ?? 0);
        }

        return [$labels, $values];
    }
}
