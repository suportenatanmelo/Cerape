<?php

namespace Tests\Feature;

use App\Filament\Widgets\AcolhidosCriadosLineChart;
use App\Filament\Widgets\DashboardEntradasAltasChart;
use App\Filament\Widgets\DemandasAcolhidosLineChart;
use App\Filament\Widgets\UsuariosCriadosLineChart;
use App\Filament\Widgets\UsuariosVinculadosAcolhidoLineChart;
use Illuminate\Support\Facades\DB;
use ReflectionMethod;
use Tests\TestCase;

class FilamentWidgetsSqliteCompatibilityTest extends TestCase
{
    public function test_chart_widgets_can_render_with_sqlite_driver(): void
    {
        $this->assertSame('sqlite', DB::connection()->getDriverName());

        foreach ($this->widgets() as $widgetClass) {
            $widget = new $widgetClass();

            if (property_exists($widget, 'filter')) {
                $widget->filter = 'anual';
            }

            $method = new ReflectionMethod($widget, 'getData');
            $method->setAccessible(true);
            $data = $method->invoke($widget);

            $this->assertIsArray($data);
            $this->assertArrayHasKey('labels', $data);
            $this->assertArrayHasKey('datasets', $data);
        }
    }

    /**
     * @return array<int, class-string>
     */
    private function widgets(): array
    {
        return [
            DashboardEntradasAltasChart::class,
            AcolhidosCriadosLineChart::class,
            DemandasAcolhidosLineChart::class,
            UsuariosCriadosLineChart::class,
            UsuariosVinculadosAcolhidoLineChart::class,
        ];
    }
}
