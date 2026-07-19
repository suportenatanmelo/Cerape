<?php

namespace App\Services\Dashboard;

use App\Models\Agenda;
use App\Models\Acolhido;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DashboardService
{
    public function getCards(array $filters = []): array
    {
        $range = $this->getPeriodRange($filters);

        return [
            'total_acolhidos' => Acolhido::query()->count(),
            'acolhidos_ativos' => Acolhido::query()->where('ativo', true)->count(),
            'novos_periodo' => Acolhido::query()->whereBetween('created_at', $range)->count(),
            'consultas_hoje' => Agenda::query()->whereDate('data', today())->count(),
            'sem_documentos' => Acolhido::query()
                ->whereNull('numero_cpf')
                ->orWhereNull('numero_rg')
                ->count(),
        ];
    }

    public function getAgendaDoDia(): Collection
    {
        return Agenda::query()
            ->with(['acolhido', 'funcionario'])
            ->whereDate('data', today())
            ->orderBy('hora_inicio')
            ->get();
    }

    private function getPeriodRange(array $filters): array
    {
        if (! empty($filters['period_start']) && ! empty($filters['period_end'])) {
            return [
                \Carbon\Carbon::parse($filters['period_start'])->startOfDay(),
                \Carbon\Carbon::parse($filters['period_end'])->endOfDay(),
            ];
        }

        return $this->currentMonthRange();
    }

    private function currentMonthRange(): array
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        return [$start, $end];
    }
}
