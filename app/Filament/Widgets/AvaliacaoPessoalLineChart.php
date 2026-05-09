<?php

namespace App\Filament\Widgets;

use App\Models\AvaliacaoPessoal;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;

class AvaliacaoPessoalLineChart extends LineChartWidget
{
    protected static bool $isDiscovered = false;

    protected ?string $heading = 'Media das avaliacoes dos acolhidos';

    protected ?string $description = 'Evolucao da media geral das notas, considerando pontuacao maxima de 3.';

    protected ?string $maxHeight = '320px';

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
                    'label' => 'Media das avaliacoes',
                    'data' => $values,
                    'borderColor' => '#d97706',
                    'backgroundColor' => 'rgba(217, 119, 6, 0.12)',
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
                    'min' => 0,
                    'max' => 3,
                    'ticks' => [
                        'stepSize' => 0.5,
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
     * @return array{0: array<int, string>, 1: array<int, float>}
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
     * @return array{0: array<int, string>, 1: array<int, float>}
     */
    private function dailySeries(Carbon $start, Carbon $end, string $labelFormat): array
    {
        $averages = AvaliacaoPessoal::query()
            ->selectRaw('DATE(created_at) as period, AVG(`Total`) as average')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('period')
            ->pluck('average', 'period');

        $labels = [];
        $values = [];

        foreach (CarbonPeriod::create($start->copy()->startOfDay(), $end->copy()->startOfDay()) as $date) {
            $key = $date->format('Y-m-d');

            $labels[] = $date->format($labelFormat);
            $values[] = round((float) ($averages[$key] ?? 0), 2);
        }

        return [$labels, $values];
    }

    /**
     * @return array{0: array<int, string>, 1: array<int, float>}
     */
    private function monthlySeries(Carbon $start, Carbon $end): array
    {
        $averages = AvaliacaoPessoal::query()
            ->select([
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as period"),
                DB::raw('AVG(`Total`) as average'),
            ])
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('period')
            ->pluck('average', 'period');

        $labels = [];
        $values = [];

        foreach (CarbonPeriod::create($start->copy()->startOfMonth(), '1 month', $end->copy()->startOfMonth()) as $date) {
            $key = $date->format('Y-m');

            $labels[] = $date->translatedFormat('M/Y');
            $values[] = round((float) ($averages[$key] ?? 0), 2);
        }

        return [$labels, $values];
    }
}
