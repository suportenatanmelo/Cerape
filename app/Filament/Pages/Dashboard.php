<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardAtendimentosPorSetorChart;
use App\Filament\Widgets\DashboardEntradasAltasChart;
use App\Filament\Widgets\DashboardEvolucaoAtendimentosChart;
use App\Filament\Widgets\DashboardFaixaEtariaChart;
use App\Filament\Widgets\DashboardOrigemEncaminhamentoChart;
use App\Filament\Widgets\DashboardSituacaoAcolhidosChart;
use App\Filament\Widgets\DashboardStatsOverviewWidget;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Dashboard extends \Filament\Pages\Dashboard
{
    public static function canAccess(): bool
    {
        return Auth::user()?->hasAclPermission(User::PERMISSION_DASHBOARD_VIEW) ?? false;
    }

    public static function canView(): bool
    {
        return self::canAccess();
    }

    public function getTitle(): string
    {
        return 'Dashboard CERAPE';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DashboardStatsOverviewWidget::class,
            DashboardEntradasAltasChart::class,
            DashboardSituacaoAcolhidosChart::class,
            DashboardAtendimentosPorSetorChart::class,
            DashboardEvolucaoAtendimentosChart::class,
            DashboardFaixaEtariaChart::class,
            DashboardOrigemEncaminhamentoChart::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return [
            'default' => 1,
            'lg' => 2,
            'xl' => 3,
        ];
    }
}
