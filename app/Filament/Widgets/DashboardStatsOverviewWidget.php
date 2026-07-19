<?php

namespace App\Filament\Widgets;

use App\Models\Acolhido;
use App\Models\Agenda;
use App\Models\User;
use App\Support\PortalContext;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = -100;

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getStats(): array
    {
        $today = now();
        $start = $today->copy()->startOfDay();
        $end = $today->copy()->endOfDay();

        $totalAcolhidos = Acolhido::count();
        $acolhidosAtivos = Acolhido::where('ativo', true)->count();
        $novosMes = Acolhido::whereBetween('created_at', [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()])->count();
        $compromissosHoje = Agenda::whereBetween('data', [$start, $end])->count();
        $confirmadosHoje = Agenda::whereBetween('data', [$start, $end])
            ->where('status', 'Confirmado')
            ->count();
        $altasMes = Acolhido::where('ativo', false)
            ->whereBetween('updated_at', [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()])
            ->count();
        $estoqueBaixo = 0;
        $funcionariosAtivos = User::where('active_status', true)->whereNotNull('funcao_usuario')->count();
        $usuariosAtivos = User::where('active_status', true)->count();

        return [
            Stat::make('Total de acolhidos', (string) $totalAcolhidos)->icon('heroicon-o-user-group')->color('primary')->description('Cadastro geral'),
            Stat::make('Acolhidos ativos', (string) $acolhidosAtivos)->icon('heroicon-o-home-modern')->color('success')->description('Em tratamento'),
            Stat::make('Novos no mês', (string) $novosMes)->icon('heroicon-o-user-plus')->color('info')->description('Criados no período'),
            Stat::make('Compromissos hoje', (string) $compromissosHoje)->icon('heroicon-o-calendar-days')->color('warning')->description('Agenda do dia'),
            Stat::make('Confirmados hoje', (string) $confirmadosHoje)->icon('heroicon-o-check-badge')->color('gray')->description('Status validado'),
            Stat::make('Altas do mês', (string) $altasMes)->icon('heroicon-o-arrow-trending-down')->color('danger')->description('Saídas registradas'),
            Stat::make('Medicamentos baixos', (string) $estoqueBaixo)->icon('heroicon-o-exclamation-triangle')->color('danger')->description('Alerta de estoque'),
            Stat::make('Equipe ativa', (string) $funcionariosAtivos)->icon('heroicon-o-briefcase')->color('secondary')->description('Profissionais habilitados'),
            Stat::make('Usuários ativos', (string) $usuariosAtivos)->icon('heroicon-o-lock-closed')->color('primary')->description('Contas liberadas'),
        ];
    }
}
