<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Agendas\AgendaResource;
use App\Models\Agenda;
use App\Support\PortalContext;
use Filament\Widgets\Widget;

class DashboardAgendaWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-agenda';

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 2];

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getViewData(): array
    {
        $items = Agenda::query()
            ->with(['acolhido', 'funcionario'])
            ->whereDate('data', now())
            ->orderBy('hora_inicio')
            ->limit(8)
            ->get();

        return ['items' => $items];
    }
}
