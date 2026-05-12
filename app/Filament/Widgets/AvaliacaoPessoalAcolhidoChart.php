<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AvaliacaoPessoals\AvaliacaoPessoalResource;
use App\Models\AvaliacaoPessoal;
use Filament\Widgets\LineChartWidget;

class AvaliacaoPessoalAcolhidoChart extends LineChartWidget
{
    protected static bool $isDiscovered = false;

    protected ?string $heading = 'Evolucao das medias por avaliador';

    protected ?string $description = 'Visualizacao das medias individuais que compoem a media consolidada do acolhido.';

    protected ?string $maxHeight = '320px';

    protected int | string | array $columnSpan = 'full';

    public ?AvaliacaoPessoal $record = null;

    protected function getData(): array
    {
        if (! $this->record) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $usuarios = AvaliacaoPessoalResource::getReportData($this->record)['usuarios'];

        return [
            'datasets' => [
                [
                    'label' => 'Media individual por avaliador',
                    'data' => $usuarios->map(fn(array $item): float => round((float) $item['media'], 2))->all(),
                    'backgroundColor' => 'rgba(217, 119, 6, 0.12)',
                    'borderColor' => '#b45309',
                    'fill' => true,
                    'tension' => 0.35,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                ],
            ],
            'labels' => $usuarios->map(fn (array $item): string => $item['user']?->name ?? 'Usuario nao informado')->all(),
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
            ],
        ];
    }
}
