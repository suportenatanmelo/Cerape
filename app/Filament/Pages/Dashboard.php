<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AcolhidosCriadosLineChart;
use App\Filament\Widgets\DashboardActivityLogsWidget;
use App\Filament\Widgets\DashboardAgendaWidget;
use App\Filament\Widgets\DashboardAlertsWidget;
use App\Filament\Widgets\DashboardAtendimentosPorSetorChart;
use App\Filament\Widgets\DashboardBirthdaysWidget;
use App\Filament\Widgets\DashboardEvolucaoAtendimentosChart;
use App\Filament\Widgets\DashboardFaixaEtariaChart;
use App\Filament\Widgets\DashboardOverviewWidget;
use App\Filament\Widgets\DashboardQuickActionsWidget;
use App\Models\Agenda;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

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

    public function getWidgets(): array
    {
        return [
            DashboardOverviewWidget::class,
            AcolhidosCriadosLineChart::class,
            DashboardEvolucaoAtendimentosChart::class,
            DashboardAtendimentosPorSetorChart::class,
            DashboardFaixaEtariaChart::class,
            DashboardAlertsWidget::class,
            DashboardBirthdaysWidget::class,
            DashboardQuickActionsWidget::class,
            DashboardAgendaWidget::class,
            DashboardActivityLogsWidget::class,
        ];
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'md' => 2,
                'xl' => 4,
            ])
            ->components([
                DatePicker::make('period_start')
                    ->label('Início do período')
                    ->live(),

                DatePicker::make('period_end')
                    ->label('Fim do período')
                    ->live(),

                Select::make('acolhido_status')
                    ->label('Status do acolhido')
                    ->options([
                        null => 'Todos',
                        'active' => 'Ativo',
                        'inactive' => 'Inativo',
                    ])
                    ->live(),

                Select::make('agenda_status')
                    ->label('Status de atendimento')
                    ->options([
                        null => 'Todos',
                        'Confirmado' => 'Confirmado',
                        'Agendado' => 'Agendado',
                        'Em andamento' => 'Em andamento',
                        'Concluído' => 'Concluído',
                        'Cancelado' => 'Cancelado',
                        'Faltou' => 'Faltou',
                    ])
                    ->live(),

                Select::make('agenda_tipo')
                    ->label('Tipo de atendimento')
                    ->options(fn (): array => Agenda::query()
                        ->select('tipo')
                        ->whereNotNull('tipo')
                        ->distinct()
                        ->orderBy('tipo')
                        ->pluck('tipo', 'tipo')
                        ->all())
                    ->searchable()
                    ->placeholder('Todos')
                    ->live(),

                Select::make('responsavel_id')
                    ->label('Responsável')
                    ->options(fn (): array => User::query()
                        ->where('active_status', true)
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->placeholder('Todos')
                    ->live(),

                TextInput::make('search')
                    ->label('Busca contextual')
                    ->placeholder('Buscar acolhido, atendimento ou usuário')
                    ->live()
                    ->columnSpan('full'),
            ])
            ->statePath('filters');
    }
}
