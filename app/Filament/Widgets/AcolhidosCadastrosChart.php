<?php

namespace App\Filament\Widgets;

use App\Models\Acolhido;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Collection;

class AcolhidosCadastrosChart extends ChartWidget
{
    protected static ?int $sort = 10;

    protected ?string $heading = 'Cadastros de acolhidos';

    protected ?string $description = 'Quantidade de cadastros por dia, mês ou ano.';

    protected ?string $maxHeight = '220px';

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = [
        'default' => 1,
        'lg' => 1,
    ];

    public ?string $filter = 'month';

    public static function canView(): bool
    {
        return auth()->user()?->hasAclPermission('View:Widgets') ?? false;
    }

    protected function getType(): string
    {
        return 'pie';
    }

    /**
     * @return array<string, string>
     */
    protected function getFilters(): ?array
    {
        return [
            'day' => 'Por dia',
            'month' => 'Por mês',
            'year' => 'Por ano',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        return match ($this->filter) {
            'day' => $this->getDailyData(),
            'year' => $this->getYearlyData(),
            default => $this->getMonthlyData(),
        };
    }

    /**
     * @return array<string, mixed>
     */
    private function getDailyData(): array
    {
        $now = now();
        $records = Acolhido::query()
            ->whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
            ->selectRaw('DAY(created_at) as period, COUNT(*) as aggregate')
            ->groupByRaw('DAY(created_at)')
            ->orderByRaw('DAY(created_at)')
            ->get();

        return $this->makeChartData(
            records: $records,
            labels: fn (int $period): string => str_pad((string) $period, 2, '0', STR_PAD_LEFT).'/'.$now->format('m'),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function getMonthlyData(): array
    {
        $now = now();
        $records = Acolhido::query()
            ->whereYear('created_at', $now->year)
            ->selectRaw('MONTH(created_at) as period, COUNT(*) as aggregate')
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->get();

        return $this->makeChartData(
            records: $records,
            labels: fn (int $period): string => $this->getMonthLabel($period),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function getYearlyData(): array
    {
        $records = Acolhido::query()
            ->selectRaw('YEAR(created_at) as period, COUNT(*) as aggregate')
            ->groupByRaw('YEAR(created_at)')
            ->orderByRaw('YEAR(created_at)')
            ->get();

        return $this->makeChartData(
            records: $records,
            labels: fn (int $period): string => (string) $period,
        );
    }

    /**
     * @param  Collection<int, Acolhido>  $records
     * @return array<string, mixed>
     */
    private function makeChartData(Collection $records, callable $labels): array
    {
        if ($records->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'label' => 'Cadastros',
                        'data' => [0],
                        'backgroundColor' => ['#d1d5db'],
                        'borderColor' => ['#f9fafb'],
                    ],
                ],
                'labels' => ['Sem cadastros'],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cadastros',
                    'data' => $records->pluck('aggregate')->map(fn (mixed $value): int => (int) $value)->all(),
                    'backgroundColor' => $this->getChartColors($records->count()),
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $records
                ->pluck('period')
                ->map(fn (mixed $period): string => $labels((int) $period))
                ->all(),
        ];
    }

    /**
     * @return array<int, string>
     */
    private function getChartColors(int $count): array
    {
        $colors = [
            '#0f766e',
            '#2563eb',
            '#ca8a04',
            '#dc2626',
            '#7c3aed',
            '#16a34a',
            '#db2777',
            '#0891b2',
            '#ea580c',
            '#4f46e5',
            '#65a30d',
            '#be123c',
        ];

        return collect(range(0, max($count - 1, 0)))
            ->map(fn (int $index): string => $colors[$index % count($colors)])
            ->all();
    }

    private function getMonthLabel(int $month): string
    {
        return [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro',
        ][$month] ?? (string) $month;
    }
}
