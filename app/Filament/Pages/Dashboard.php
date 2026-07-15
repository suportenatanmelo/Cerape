<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardAgendaWidget;
use App\Filament\Widgets\DashboardAlertsWidget;
use App\Filament\Widgets\DashboardAtendimentosPorSetorChart;
use App\Filament\Widgets\DashboardBirthdaysWidget;
use App\Filament\Widgets\DashboardEntradasAltasChart;
use App\Filament\Widgets\DashboardEvolucaoAtendimentosChart;
use App\Filament\Widgets\DashboardFaixaEtariaChart;
use App\Filament\Widgets\DashboardFinanceiroWidget;
use App\Filament\Widgets\FinanceiroAcolhidoWidget;
use App\Filament\Widgets\DashboardGeneralIndicatorsWidget;
use App\Filament\Widgets\DashboardLatestActivitiesWidget;
use App\Filament\Widgets\DashboardOrigemEncaminhamentoChart;
use App\Filament\Widgets\DashboardQuickActionsWidget;
use App\Filament\Widgets\DashboardSituacaoAcolhidosChart;
use App\Filament\Widgets\DashboardStatsOverviewWidget;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        return 'Painel CERAPE';
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
            DashboardAgendaWidget::class,
            DashboardAlertsWidget::class,
            DashboardLatestActivitiesWidget::class,
            DashboardFinanceiroWidget::class,
            FinanceiroAcolhidoWidget::class,
            DashboardBirthdaysWidget::class,
            DashboardQuickActionsWidget::class,
            DashboardGeneralIndicatorsWidget::class,
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
