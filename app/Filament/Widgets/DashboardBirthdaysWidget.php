<?php

namespace App\Filament\Widgets;

use App\Models\Acolhido;
use App\Models\User;
use App\Support\PortalContext;
use Filament\Widgets\Widget;

class DashboardBirthdaysWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-birthdays';

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 1];

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getViewData(): array
    {
        $acolhidos = Acolhido::query()
            ->whereMonth('data_nascimento', now()->month)
            ->orderBy('data_nascimento')
            ->limit(5)
            ->get();

        $funcionarios = User::query()
            ->whereMonth('data_nascimento', now()->month)
            ->where('active_status', true)
            ->limit(5)
            ->get();

        return ['acolhidos' => $acolhidos, 'funcionarios' => $funcionarios];
    }
}
