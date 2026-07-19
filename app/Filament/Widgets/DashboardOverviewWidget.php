<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Agendas\AgendaResource;
use App\Filament\Resources\ProntuariosEvolucao\ProntuarioEvolucaoResource;
use App\Filament\Resources\Saudes\SaudeResource;
use App\Models\Agenda;
use App\Services\Dashboard\DashboardService;
use App\Support\PortalContext;
use Filament\Widgets\Widget;

class DashboardOverviewWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-overview';

    protected int|string|array $columnSpan = 'full';

    public ?array $pageFilters = null;

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getViewData(): array
    {
        $service = new DashboardService();

        $cards = $service->getCards($this->pageFilters ?? []);

        $upcomingAppointments = Agenda::query()
            ->with('acolhido')
            ->whereDate('data', now())
            ->orderBy('hora_inicio')
            ->limit(6)
            ->get();

        return [
            'greetingName' => auth()->user()?->name ?? 'Administrador',
            'cards' => $cards,
            'upcomingAppointments' => $upcomingAppointments,
        ];
    }
}
