<?php

namespace App\Filament\Widgets;

use App\Models\Acolhido;
use App\Models\DemandaAcolhido;
use App\Support\PortalContext;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;

class DemandasAcolhidosLineChart extends LineChartWidget
{
    use HasFiltersSchema;

    protected static bool $isDiscovered = false;

    protected static ?int $sort = 4;

    protected ?string $heading = 'Demandas de acolhidos';

    protected ?string $description = 'Acompanhe a quantidade de demandas registradas por acolhido em diferentes periodos.';

    protected ?string $maxHeight = '320px';

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'xl' => 1,
    ];

    protected bool $hasDeferredFilters = true;

    public ?string $filter = 'semanal';

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getFilters(): ?array
    {
        return [
            'semanal' => 'Semanal',
            'mensal' => 'Mensal',
            'semestral' => 'Semestral',
            'anual' => 'Anual',
        ];
    }

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('acolhido_id')
                ->label('Acolhido')
                ->options(fn (): array => Acolhido::query()
                    ->orderBy('nome_completo_paciente')
                    ->pluck('nome_completo_paciente', 'id')
                    ->all())
                ->default(fn (): ?int => Acolhido::query()
                    ->orderBy('nome_completo_paciente')
                    ->value('id'))
                ->searchable()
                ->preload()
                ->native(false)
                ->selectablePlaceholder(false)
                ->required(),
        ]);
    }

    protected function getData(): array
    {
        [$labels, $values] = $this->getSeries();

        $acolhido = $this->getSelectedAcolhido();

        return [
            'datasets' => [
                [
                    'label' => $acolhido?->nome_completo_paciente
                        ? 'Demandas de ' . $acolhido->nome_completo_paciente
                        : 'Demandas do acolhido',
                    'data' => $values,
                    'borderColor' => '#0891b2',
                    'backgroundColor' => 'rgba(8, 145, 178, 0.12)',
                    'fill' => true,
                    'tension' => 0.35,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
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
        $acolhidoId = $this->filters['acolhido_id'] ?? null;

        if (blank($acolhidoId)) {
            return [[], []];
        }

        return match ($this->filter) {
            'mensal' => $this->dailySeries((int) $acolhidoId, now()->startOfMonth(), now()->endOfMonth(), 'd/m'),
            'semestral' => $this->monthlySeries((int) $acolhidoId, now()->subMonths(5)->startOfMonth(), now()->endOfMonth()),
            'anual' => $this->monthlySeries((int) $acolhidoId, now()->subMonths(11)->startOfMonth(), now()->endOfMonth()),
            default => $this->dailySeries((int) $acolhidoId, now()->subDays(6)->startOfDay(), now()->endOfDay(), 'd/m'),
        };
    }

    /**
     * @return array{0: array<int, string>, 1: array<int, int>}
     */
    private function dailySeries(int $acolhidoId, Carbon $start, Carbon $end, string $labelFormat): array
    {
        $totals = DemandaAcolhido::query()
            ->selectRaw('DATE(created_at) as period, COUNT(*) as total')
            ->where('acolhido_id', $acolhidoId)
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
    private function monthlySeries(int $acolhidoId, Carbon $start, Carbon $end): array
    {
        $totals = DemandaAcolhido::query()
            ->select([
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as period"),
                DB::raw('COUNT(*) as total'),
            ])
            ->where('acolhido_id', $acolhidoId)
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

    private function getSelectedAcolhido(): ?Acolhido
    {
        $acolhidoId = $this->filters['acolhido_id'] ?? null;

        if (blank($acolhidoId)) {
            return null;
        }

        return Acolhido::query()->find($acolhidoId);
    }
}
