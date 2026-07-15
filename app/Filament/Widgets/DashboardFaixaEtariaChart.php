<?php

namespace App\Filament\Widgets;

use App\Models\Acolhido;
use App\Support\PortalContext;
use Filament\Widgets\ChartWidget;

class DashboardFaixaEtariaChart extends ChartWidget
{
    protected ?string $heading = 'Faixa etária';

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 1];

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $bands = ['18-25' => 0, '26-35' => 0, '36-45' => 0, '46-55' => 0, '56+' => 0];

        Acolhido::whereNotNull('data_nascimento')->get(['data_nascimento'])->each(function (Acolhido $acolhido) use (&$bands): void {
            $age = $acolhido->data_nascimento?->age;
            if ($age === null) {
                return;
            }
            if ($age <= 25) $bands['18-25']++;
            elseif ($age <= 35) $bands['26-35']++;
            elseif ($age <= 45) $bands['36-45']++;
            elseif ($age <= 55) $bands['46-55']++;
            else $bands['56+']++;
        });

        return [
            'labels' => array_keys($bands),
            'datasets' => [[
                'label' => 'Acolhidos',
                'data' => array_values($bands),
                'backgroundColor' => '#ca8a04',
            ]],
        ];
    }
}
