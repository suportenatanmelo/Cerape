<?php

namespace Tests\Unit;

use App\Filament\Widgets\AcolhidoEvolucaoLineChart;
use App\Filament\Widgets\AcolhidosCriadosLineChart;
use App\Filament\Widgets\AvaliacaoPessoalLineChart;
use App\Filament\Widgets\DashboardEntradasAltasChart;
use App\Filament\Widgets\DashboardEvolucaoAtendimentosChart;
use App\Filament\Widgets\DemandasAcolhidosLineChart;
use PHPUnit\Framework\TestCase;

class FilamentWidgetsTest extends TestCase
{
    public function test_widget_classes_can_be_loaded_without_import_conflicts(): void
    {
        $classes = [
            AcolhidoEvolucaoLineChart::class,
            AcolhidosCriadosLineChart::class,
            AvaliacaoPessoalLineChart::class,
            DashboardEntradasAltasChart::class,
            DashboardEvolucaoAtendimentosChart::class,
            DemandasAcolhidosLineChart::class,
        ];

        foreach ($classes as $class) {
            $this->assertTrue(class_exists($class), "Expected {$class} to be loadable.");
        }
    }
}
