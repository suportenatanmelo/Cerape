<?php

namespace App\Filament\Widgets;

use App\Models\Acolhido;
use App\Models\AvaliacaoPessoal;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;

class AvaliacaoPessoalLineChart extends LineChartWidget
{
    protected static bool $isDiscovered = false;

    protected string $view = 'filament.widgets.avaliacao-pessoal-line-chart';

    protected ?string $heading = 'Relatorio de evolucao do acolhido';

    protected ?string $description = 'Selecione um acolhido para comparar a media das avaliacoes com a media consolidada dos avaliadores em cada periodo.';

    protected ?string $maxHeight = '280px';

    protected int | string | array $columnSpan = 'full';

    public ?string $filter = 'semanal';

    public ?string $acolhidoId = null;

    public function mount(): void
    {
        $this->acolhidoId = (string) (Acolhido::query()
            ->orderBy('nome_completo_paciente')
            ->value('id') ?? '');

        parent::mount();
    }

    public function updatedAcolhidoId(): void
    {
        $this->cachedData = null;
    }

    public function getAcolhidoOptions(): array
    {
        return Acolhido::query()
            ->orderBy('nome_completo_paciente')
            ->pluck('nome_completo_paciente', 'id')
            ->all();
    }

    public function updatedFilter(): void
    {
        $this->cachedData = null;
    }

    protected function getData(): array
    {
        [$labels, $rawAverages, $voterAverages] = $this->getSeries();
        $acolhido = $this->getSelectedAcolhido();
        $nomeAcolhido = $acolhido?->nome_completo_paciente ?? 'acolhido selecionado';

        return [
            'datasets' => [
                [
                    'label' => 'Media das avaliacoes de ' . $nomeAcolhido,
                    'data' => $rawAverages,
                    'borderColor' => '#d97706',
                    'backgroundColor' => 'rgba(217, 119, 6, 0.12)',
                    'fill' => true,
                    'tension' => 0.35,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                ],
                [
                    'label' => 'Media consolidada dos avaliadores de ' . $nomeAcolhido,
                    'data' => $voterAverages,
                    'borderColor' => '#0f766e',
                    'backgroundColor' => 'rgba(15, 118, 110, 0.10)',
                    'fill' => false,
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
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
        ];
    }

    /**
     * @return array{0: array<int, string>, 1: array<int, float>, 2: array<int, float>}
     */
    private function getSeries(): array
    {
        $acolhidoId = $this->acolhidoId;

        if (blank($acolhidoId)) {
            return [[], [], []];
        }

        return match ($this->filter) {
            'mensal' => $this->dailySeries((int) $acolhidoId, now()->startOfMonth(), now()->endOfMonth(), 'd/m'),
            'semestral' => $this->monthlySeries((int) $acolhidoId, now()->subMonths(5)->startOfMonth(), now()->endOfMonth()),
            'anual' => $this->monthlySeries((int) $acolhidoId, now()->subMonths(11)->startOfMonth(), now()->endOfMonth()),
            default => $this->dailySeries((int) $acolhidoId, now()->subDays(6)->startOfDay(), now()->endOfDay(), 'd/m'),
        };
    }

    /**
     * @return array{0: array<int, string>, 1: array<int, float>, 2: array<int, float>}
     */
    private function dailySeries(int $acolhidoId, Carbon $start, Carbon $end, string $labelFormat): array
    {
        $rawAverages = AvaliacaoPessoal::query()
            ->selectRaw('DATE(created_at) as period, AVG(`Total`) as average')
            ->where('acolhido_id', $acolhidoId)
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('period')
            ->pluck('average', 'period');

        $voterAverages = AvaliacaoPessoal::query()
            ->selectRaw('DATE(created_at) as period, user_id, AVG(`Total`) as average')
            ->where('acolhido_id', $acolhidoId)
            ->whereNotNull('user_id')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('period', 'user_id')
            ->get()
            ->groupBy('period')
            ->map(fn ($periodRows): float => (float) $periodRows->avg('average'));

        $labels = [];
        $rawValues = [];
        $voterValues = [];

        foreach (CarbonPeriod::create($start->copy()->startOfDay(), $end->copy()->startOfDay()) as $date) {
            $key = $date->format('Y-m-d');

            $labels[] = $date->format($labelFormat);
            $rawValues[] = round((float) ($rawAverages[$key] ?? 0), 2);
            $voterValues[] = round((float) ($voterAverages[$key] ?? 0), 2);
        }

        return [$labels, $rawValues, $voterValues];
    }

    /**
     * @return array{0: array<int, string>, 1: array<int, float>, 2: array<int, float>}
     */
    private function monthlySeries(int $acolhidoId, Carbon $start, Carbon $end): array
    {
        $rawAverages = AvaliacaoPessoal::query()
            ->select([
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as period"),
                DB::raw('AVG(`Total`) as average'),
            ])
            ->where('acolhido_id', $acolhidoId)
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('period')
            ->pluck('average', 'period');

        $voterAverages = AvaliacaoPessoal::query()
            ->select([
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as period"),
                'user_id',
                DB::raw('AVG(`Total`) as average'),
            ])
            ->where('acolhido_id', $acolhidoId)
            ->whereNotNull('user_id')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('period', 'user_id')
            ->get()
            ->groupBy('period')
            ->map(fn ($periodRows): float => (float) $periodRows->avg('average'));

        $labels = [];
        $rawValues = [];
        $voterValues = [];

        foreach (CarbonPeriod::create($start->copy()->startOfMonth(), '1 month', $end->copy()->startOfMonth()) as $date) {
            $key = $date->format('Y-m');

            $labels[] = $date->translatedFormat('M/Y');
            $rawValues[] = round((float) ($rawAverages[$key] ?? 0), 2);
            $voterValues[] = round((float) ($voterAverages[$key] ?? 0), 2);
        }

        return [$labels, $rawValues, $voterValues];
    }

    private function getSelectedAcolhido(): ?Acolhido
    {
        $acolhidoId = $this->acolhidoId;

        if (blank($acolhidoId)) {
            return null;
        }

        return Acolhido::query()->find($acolhidoId);
    }
}
