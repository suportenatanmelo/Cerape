<?php

namespace App\Services\Dashboard;

use App\Models\Agenda;
use App\Models\Acolhido;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\Enums\SituacaoAcolhido;

class DashboardService
{
    public function getCards(): array
    {
        return [
            'total_acolhidos' => Acolhido::query()->count(),
            'acolhidos_ativos' => Acolhido::query()->where('ativo', true)->count(),
            'novos_acolhimentos_mes' => Acolhido::query()->whereBetween('created_at', $this->currentMonthRange())->count(),
            'consultas_hoje' => Agenda::query()->whereDate('data', today())->count(),
        ];
    }

    /**
     * Retorna contadores por situação do acolhido
     *
     * @return array<string,int>
     */
    public function getSituacaoCounts(): array
    {
        $counts = [];

        foreach (SituacaoAcolhido::cases() as $case) {
            $counts[$case->value] = Acolhido::query()->where('situacao', $case->value)->count();
        }

        return $counts;
    }

    public function countBySituacao(SituacaoAcolhido $situacao): int
    {
        return Acolhido::query()->where('situacao', $situacao->value)->count();
    }

    public function getAgendaDoDia(): Collection
    {
        return Agenda::query()
            ->with(['acolhido', 'funcionario'])
            ->whereDate('data', today())
            ->orderBy('hora_inicio')
            ->get();
    }

    private function currentMonthRange(): array
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        return [$start, $end];
    }
}
