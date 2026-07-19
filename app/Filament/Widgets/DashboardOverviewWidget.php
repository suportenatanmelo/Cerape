<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Acolhidos\AcolhidoResource;
use App\Filament\Resources\Agendas\AgendaResource;
use App\Filament\Resources\ProntuariosEvolucao\ProntuarioEvolucaoResource;
use App\Filament\Resources\Saudes\SaudeResource;
use App\Models\Acolhido;
use App\Models\Agenda;
use App\Models\User;
use App\Services\Dashboard\DashboardService;
use App\Support\PortalContext;
use Filament\Widgets\Widget;

class DashboardOverviewWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-overview';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getViewData(): array
    {
        $service = new DashboardService();

        $upcomingAppointments = Agenda::query()
            ->with('acolhido')
            ->whereDate('data', now())
            ->orderBy('hora_inicio')
            ->limit(4)
            ->get();

        $birthdays = collect();

        $acolhidos = Acolhido::query()
            ->select(['id', 'nome_completo_paciente', 'avatar', 'data_nascimento'])
            ->whereMonth('data_nascimento', now()->month)
            ->orderByRaw('DAY(data_nascimento)')
            ->limit(4)
            ->get();

        foreach ($acolhidos as $acolhido) {
            $birthdays->push([
                'name' => $acolhido->nome_completo_paciente,
                'avatar' => $acolhido->avatar,
                'role' => 'Acolhido',
                'birthday' => $acolhido->data_nascimento?->format('d/m'),
                'age' => $acolhido->data_nascimento?->age,
                'label' => 'Acolhido',
            ]);
        }

        $funcionarios = User::query()
            ->select(['id', 'name', 'avatar', 'funcao_usuario', 'data_nascimento'])
            ->whereMonth('data_nascimento', now()->month)
            ->where('active_status', true)
            ->orderByRaw('DAY(data_nascimento)')
            ->limit(4)
            ->get();

        foreach ($funcionarios as $funcionario) {
            $birthdays->push([
                'name' => $funcionario->name,
                'avatar' => $funcionario->avatar,
                'role' => $funcionario->funcao_usuario ?? 'Equipe',
                'birthday' => $funcionario->data_nascimento?->format('d/m'),
                'age' => $funcionario->data_nascimento?->age,
                'label' => 'Equipe',
            ]);
        }

        $birthdays = $birthdays->sortBy(fn ($item) => $item['birthday'])->values()->take(5);

        return [
            'greetingName' => auth()->user()?->name ?? 'Administrador',
            'cards' => $service->getCards(),
            'upcomingAppointments' => $upcomingAppointments,
            'birthdays' => $birthdays,
            'actions' => [
                ['label' => 'Novo acolhido', 'url' => AcolhidoResource::getUrl('create')],
                ['label' => 'Nova consulta', 'url' => AgendaResource::getUrl('create')],
                ['label' => 'Nova evolução', 'url' => ProntuarioEvolucaoResource::getUrl('create')],
                ['label' => 'Fluxo de saúde', 'url' => SaudeResource::getUrl('index')],
            ],
        ];
    }
}
