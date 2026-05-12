<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AvaliacaoPessoals\AvaliacaoPessoalResource;
use App\Models\AvaliacaoPessoal;
use Filament\Widgets\LineChartWidget;

class AvaliacaoPessoalPeriodoComparativoChart extends LineChartWidget
{
    protected static bool $isDiscovered = false;

    protected ?string $heading = 'Comparativo entre periodo atual e anterior';

    protected ?string $description = 'Comparacao da media das avaliacoes e da media consolidada dos avaliadores entre periodos consecutivos.';

    protected ?string $maxHeight = '320px';

    protected int | string | array $columnSpan = 'full';

    public ?AvaliacaoPessoal $record = null;

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

    public function getDescription(): ?string
    {
        if (! $this->record) {
            return 'Selecione uma avaliacao para comparar os periodos.';
        }

        $comparison = AvaliacaoPessoalResource::calculatePeriodComparison(
            $this->record->acolhido_id,
            $this->filter ?? 'semanal',
        );

        return $comparison['current_label'] . ' comparado com ' . $comparison['previous_label'] . '.';
    }

    protected function getData(): array
    {
        if (! $this->record) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $comparison = AvaliacaoPessoalResource::calculatePeriodComparison(
            $this->record->acolhido_id,
            $this->filter ?? 'semanal',
        );

        return [
            'labels' => [
                'Periodo anterior',
                'Periodo atual',
            ],
            'datasets' => [
                [
                    'label' => 'Media das avaliacoes',
                    'data' => [
                        round((float) $comparison['raw_previous'], 2),
                        round((float) $comparison['raw_current'], 2),
                    ],
                    'backgroundColor' => 'rgba(217, 119, 6, 0.12)',
                    'borderColor' => '#b45309',
                    'fill' => false,
                    'tension' => 0.35,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                ],
                [
                    'label' => 'Media consolidada dos avaliadores',
                    'data' => [
                        round((float) $comparison['consolidated_previous'], 2),
                        round((float) $comparison['consolidated_current'], 2),
                    ],
                    'backgroundColor' => 'rgba(15, 118, 110, 0.10)',
                    'borderColor' => '#0f766e',
                    'fill' => false,
                    'tension' => 0.35,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                ],
            ],
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
}
