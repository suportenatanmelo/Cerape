<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AvaliacaoPessoals\AvaliacaoPessoalResource;
use App\Models\AvaliacaoPessoal;
use Filament\Widgets\ChartWidget;

class AvaliacaoPessoalAcolhidoChart extends ChartWidget
{
    protected static bool $isDiscovered = false;

    protected ?string $heading = 'Medias por usuario avaliador';

    protected ?string $description = 'Comparativo das medias individuais usadas para formar a Media de todos.';

    protected ?string $maxHeight = '320px';

    protected int | string | array $columnSpan = 'full';

    public ?AvaliacaoPessoal $record = null;

    protected function getType(): string
    {
        return 'bar';
    }

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
                    'label' => 'Media do usuario',
                    'data' => $usuarios->map(fn(array $item): float => round((float) $item['media'], 2))->all(),
                    'backgroundColor' => '#d97706',
                    'borderColor' => '#b45309',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $usuarios->map(fn(array $item): string => $item['user']?->name ?? 'Usuario nao informado')->all(),
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
        ];
    }
}
