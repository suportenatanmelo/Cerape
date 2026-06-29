<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Acolhidos\AcolhidoResource;
use App\Filament\Resources\Agendas\AgendaResource;
use App\Filament\Resources\ProntuariosEvolucao\ProntuarioEvolucaoResource;
use App\Filament\Resources\Saudes\SaudeResource;
use App\Filament\Resources\Users\UserResource;
use App\Support\PortalContext;
use Illuminate\Support\Facades\Route;
use Filament\Widgets\Widget;

class DashboardQuickActionsWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-quick-actions';

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 2];

    public static function canView(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    protected function getViewData(): array
    {
        $items = [
                ['label' => 'Novo Acolhido', 'url' => AcolhidoResource::getUrl('create'), 'icon' => 'heroicon-o-user-plus'],
                ['label' => 'Nova Consulta', 'url' => AgendaResource::getUrl('create'), 'icon' => 'heroicon-o-calendar-days'],
                ['label' => 'Nova Evolução', 'url' => ProntuarioEvolucaoResource::getUrl('create'), 'icon' => 'heroicon-o-document-plus'],
                ['label' => 'Novo Agendamento', 'url' => AgendaResource::getUrl('create'), 'icon' => 'heroicon-o-clock'],
                ['label' => 'Nova Ficha de Saúde', 'url' => SaudeResource::getUrl('create'), 'icon' => 'heroicon-o-heart'],
                ['label' => 'Consulta de Saúde', 'url' => SaudeResource::getUrl('index'), 'icon' => 'heroicon-o-beaker'],
                ['label' => 'Emitir Relatório', 'url' => ProntuarioEvolucaoResource::getUrl('index'), 'icon' => 'heroicon-o-printer'],
        ];

        if (Route::has('filament.admin.resources.users.create')) {
            $items[] = ['label' => 'Novo Funcionário', 'url' => UserResource::getUrl('create'), 'icon' => 'heroicon-o-briefcase'];
        }

        return ['items' => $items];
    }
}
