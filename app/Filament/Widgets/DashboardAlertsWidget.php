<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Acolhidos\AcolhidoResource;
use App\Models\Acolhido;
use App\Models\Agenda;
use App\Models\DemandaAcolhido;
use App\Models\User;
use App\Support\PortalContext;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class DashboardAlertsWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-alerts';

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 1];

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getViewData(): array
    {
        $today = Carbon::today();

        $documentosFaltando = Acolhido::query()
            ->whereNull('numero_cpf')
            ->orWhereNull('numero_rg')
            ->count();

        $consultasHoje = Agenda::query()
            ->whereDate('data', $today)
            ->count();

        $aniversariantesHoje = Acolhido::query()
            ->whereMonth('data_nascimento', $today->month)
            ->whereDay('data_nascimento', $today->day)
            ->count()
            + User::query()
                ->where('active_status', true)
                ->whereMonth('data_nascimento', $today->month)
                ->whereDay('data_nascimento', $today->day)
                ->count();

        $pendencias = DemandaAcolhido::query()
            ->whereBetween('retorno_previsto_em', [$today->startOfDay(), $today->copy()->addDays(7)->endOfDay()])
            ->count();

        return [
            'items' => [
                ['label' => 'Documentos incompletos', 'value' => $documentosFaltando, 'color' => 'warning'],
                ['label' => 'Consultas hoje', 'value' => $consultasHoje, 'color' => 'primary'],
                ['label' => 'Aniversariantes hoje', 'value' => $aniversariantesHoje, 'color' => 'success'],
                ['label' => 'Demandas pendentes', 'value' => $pendencias, 'color' => 'danger'],
            ],
        ];
    }
}
