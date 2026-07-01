<?php

namespace App\Services\Agenda;

use App\Models\Agenda;
use App\Models\Acolhido;
use App\Models\DemandaAcolhido;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AgendaService
{
    public function getTypeColors(): array
    {
        return [
            'Psicologia' => '#22c55e',
            'Enfermagem' => '#3b82f6',
            'Médico' => '#ef4444',
            'Medico' => '#ef4444',
            'Serviço Social' => '#eab308',
            'Servico Social' => '#eab308',
            'Jurídico' => '#8b5cf6',
            'Juridico' => '#8b5cf6',
            'Terapia Ocupacional' => '#f97316',
            'Outros' => '#64748b',
            'Consulta' => '#0ea5e9',
            'Reunião' => '#14b8a6',
            'Reuniao' => '#14b8a6',
            'Visita' => '#f59e0b',
            'Procedimento' => '#f43f5e',
            'Plantão' => '#a855f7',
            'Plantao' => '#a855f7',
            'Evento' => '#6b7280',
        ];
    }

    public function colorFor(Agenda $agenda): string
    {
        foreach ($this->normalizeTypes($agenda->tipo) as $type) {
            if (isset($this->getTypeColors()[$type])) {
                return $this->getTypeColors()[$type];
            }
        }

        return $agenda->cor ?: '#3b82f6';
    }

    /**
     * @return array<int, string>
     */
    private function normalizeTypes(mixed $type): array
    {
        if (is_array($type)) {
            return array_filter(array_map([$this, 'normalizeLabel'], $type));
        }

        return array_filter(array_map([$this, 'normalizeLabel'], array_filter(array_map('trim', explode(',', (string) $type)))));
    }

    private function normalizeLabel(string $value): string
    {
        $value = trim($value);

        return strtr($value, [
            'á' => 'a',
            'à' => 'a',
            'ã' => 'a',
            'â' => 'a',
            'é' => 'e',
            'ê' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ú' => 'u',
            'ç' => 'c',
        ]);
    }

    public function getEventosDoDia(Carbon|string|null $date = null): Collection
    {
        $date = $date instanceof Carbon ? $date : Carbon::parse($date ?? today());

        return Agenda::query()
            ->with(['acolhido', 'funcionario'])
            ->whereDate('data', $date->toDateString())
            ->orderBy('hora_inicio')
            ->get();
    }

    public function getEventosDaSemana(Carbon|string|null $date = null): Collection
    {
        $date = $date instanceof Carbon ? $date : Carbon::parse($date ?? today());

        return Agenda::query()
            ->with(['acolhido', 'funcionario'])
            ->whereBetween('data', [$date->copy()->startOfWeek()->toDateString(), $date->copy()->endOfWeek()->toDateString()])
            ->orderBy('data')
            ->orderBy('hora_inicio')
            ->get();
    }

    public function getProximosEventos(int $limit = 5): Collection
    {
        return Agenda::query()
            ->with(['acolhido', 'funcionario'])
            ->whereDate('data', '>=', today())
            ->orderBy('data')
            ->orderBy('hora_inicio')
            ->limit($limit)
            ->get();
    }

    public function getAcolhidoOptions(): array
    {
        return Acolhido::query()->orderBy('nome_completo_paciente')->pluck('nome_completo_paciente', 'id')->all();
    }

    public function getFuncionarioOptions(): array
    {
        return User::query()->orderBy('name')->pluck('name', 'id')->all();
    }

    public function getResumo(): array
    {
        $todayEvents = Agenda::query()->whereDate('data', today())->count();
        $consultas = Agenda::query()->whereDate('data', today())->where('tipo', 'like', '%Consulta%')->count();
        $reunioes = Agenda::query()->whereDate('data', today())->where('tipo', 'like', '%Reuni%')->count();
        $visitas = Agenda::query()->whereDate('data', today())->where('tipo', 'like', '%Visita%')->count();
        $plantoes = Agenda::query()->whereDate('data', today())->where('tipo', 'like', '%Plant%')->count();
        $eventos = Agenda::query()->whereDate('data', today())->count();

        return compact('todayEvents', 'consultas', 'reunioes', 'visitas', 'plantoes', 'eventos');
    }

    public function syncAgendaFromDemanda(DemandaAcolhido $demanda): ?Agenda
    {
        if (blank($demanda->acolhido_id)) {
            return null;
        }

        $start = $demanda->saida_prevista_em instanceof Carbon
            ? $demanda->saida_prevista_em
            : Carbon::parse($demanda->saida_prevista_em ?? now());

        $end = $demanda->retorno_previsto_em instanceof Carbon
            ? $demanda->retorno_previsto_em
            : Carbon::parse($demanda->retorno_previsto_em ?? $start->copy()->addHour());

        $marker = '#DEMANDA:' . $demanda->getKey();

        return Agenda::query()
            ->updateOrCreate(
                [
                    'acolhido_id' => $demanda->acolhido_id,
                    'tipo' => 'Demanda assistencial',
                    'descricao' => $marker,
                ],
                [
                    'titulo' => $demanda->demanda ?: 'Demanda assistencial',
                    'funcionario_id' => null,
                    'data' => $start->toDateString(),
                    'hora_inicio' => $start->format('H:i'),
                    'hora_fim' => $end->format('H:i'),
                    'status' => 'Agendado',
                    'cor' => '#0ea5e9',
                    'dia_todo' => false,
                    'notificar' => true,
                ],
            );
    }

    public function removeAgendaFromDemanda(DemandaAcolhido $demanda): void
    {
        Agenda::query()
            ->where('acolhido_id', $demanda->acolhido_id)
            ->where('tipo', 'Demanda assistencial')
            ->where('descricao', '#DEMANDA:' . $demanda->getKey())
            ->delete();
    }
}
