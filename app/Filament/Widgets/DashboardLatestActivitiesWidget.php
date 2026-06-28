<?php

namespace App\Filament\Widgets;

use App\Models\Acolhido;
use App\Models\Agenda;
use App\Models\ProntuarioEvolucao;
use App\Models\User;
use App\Support\PortalContext;
use Illuminate\Support\Collection;
use Filament\Widgets\Widget;

class DashboardLatestActivitiesWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-latest-activities';

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 2];

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getViewData(): array
    {
        $items = collect()
            ->merge($this->recentAcolhidos())
            ->merge($this->recentAgendas())
            ->merge($this->recentProntuarios())
            ->sortByDesc('datetime')
            ->take(6)
            ->values();

        return ['items' => $items];
    }

    private function recentAcolhidos(): Collection
    {
        return Acolhido::query()
            ->latest('created_at')
            ->limit(2)
            ->get()
            ->map(fn (Acolhido $item) => [
                'usuario' => auth()->user()?->name ?? 'Sistema',
                'acao' => 'Cadastro de acolhido: '.$item->nome_completo_paciente,
                'modulo' => 'Acolhidos',
                'data' => $item->created_at?->format('d/m/Y'),
                'hora' => $item->created_at?->format('H:i'),
                'datetime' => $item->created_at,
            ]);
    }

    private function recentAgendas(): Collection
    {
        return Agenda::query()
            ->latest('updated_at')
            ->limit(2)
            ->get()
            ->map(fn (Agenda $item) => [
                'usuario' => $item->funcionario?->name ?? 'Sistema',
                'acao' => 'Inclusão de consulta: '.($item->titulo ?: 'Agendamento'),
                'modulo' => 'Agenda',
                'data' => $item->updated_at?->format('d/m/Y'),
                'hora' => $item->updated_at?->format('H:i'),
                'datetime' => $item->updated_at,
            ]);
    }

    private function recentProntuarios(): Collection
    {
        return ProntuarioEvolucao::query()
            ->with('user')
            ->latest('updated_at')
            ->limit(2)
            ->get()
            ->map(fn (ProntuarioEvolucao $item) => [
                'usuario' => $item->user?->name ?? 'Sistema',
                'acao' => 'Alteração de prontuário',
                'modulo' => 'Prontuários',
                'data' => $item->updated_at?->format('d/m/Y'),
                'hora' => $item->updated_at?->format('H:i'),
                'datetime' => $item->updated_at,
            ]);
    }
}
