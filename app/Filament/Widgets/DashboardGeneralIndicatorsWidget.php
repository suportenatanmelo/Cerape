<?php

namespace App\Filament\Widgets;

use App\Models\Acolhido;
use App\Models\Agenda;
use App\Models\User;
use App\Support\PortalContext;
use Filament\Widgets\Widget;

class DashboardGeneralIndicatorsWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-general-indicators';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getViewData(): array
    {
        return [
            'taxaOcupacao' => 82,
            'mediaPermanencia' => 96,
            'altasMes' => Acolhido::where('ativo', false)->whereMonth('updated_at', now()->month)->count(),
            'desistencias' => 0,
            'consultasRealizadas' => Agenda::whereMonth('data', now()->month)->count(),
            'usuariosAtivos' => User::where('active_status', true)->count(),
        ];
    }
}
