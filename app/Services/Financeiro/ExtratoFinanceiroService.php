<?php

namespace App\Services\Financeiro;

use App\Data\Financeiro\ExtratoFinanceiroEntryData;
use App\Models\Acolhido;
use App\Models\CompraInterna;
use App\Models\DiariaTrabalho;
use App\Models\MovimentacaoFinanceira;
use App\Models\SaqueFinanceiro;
use App\Models\TransferenciaFamilia;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ExtratoFinanceiroService
{
    /**
     * @return array{
     *     acolhido: Acolhido|null,
     *     entries: Collection<int, ExtratoFinanceiroEntryData>,
     *     summary: array<string, mixed>,
     *     timeline: Collection<int, ExtratoFinanceiroEntryData>
     * }
     */
    public function build(?int $acolhidoId, array $filters = []): array
    {
        $acolhido = $acolhidoId ? Acolhido::query()->with('user')->find($acolhidoId) : null;

        if (! $acolhido instanceof Acolhido) {
            return [
                'acolhido' => null,
                'entries' => collect(),
                'summary' => $this->emptySummary(),
                'timeline' => collect(),
            ];
        }

        $entries = $this->collectEntries($acolhido->id, $filters)
            ->sortBy(fn (ExtratoFinanceiroEntryData $entry): string => $entry->data->format('Y-m-d H:i:s') . '-' . str_pad((string) ($entry->sourceId ?? 0), 10, '0', STR_PAD_LEFT))
            ->values();

        $summary = $this->summarize($entries);

        return [
            'acolhido' => $acolhido,
            'entries' => $entries,
            'summary' => $summary,
            'timeline' => $entries->sortByDesc('data')->take(10)->values(),
        ];
    }

    /**
     * @return Collection<int, ExtratoFinanceiroEntryData>
     */
    public function collectEntries(int $acolhidoId, array $filters = []): Collection
    {
        $entries = collect();

        $diarias = $this->applyCommonFilters(
            DiariaTrabalho::query()->with(['empresaParceira', 'frenteTrabalho'])->where('acolhido_id', $acolhidoId),
            $filters,
        )->get();

        foreach ($diarias as $diaria) {
            if ($diaria->situacao === 'cancelado') {
                continue;
            }

            $credito = (float) $diaria->valor_acolhido;

            $entries->push(new ExtratoFinanceiroEntryData(
                data: CarbonImmutable::parse($diaria->data),
                tipo: 'Diaria',
                descricao: $diaria->tipo_servico ?: 'Diária de trabalho',
                empresa: $diaria->empresaParceira?->nome,
                credito: $credito,
                debito: 0.0,
                saldoAposLancamento: 0.0,
                responsavel: null,
                observacoes: $diaria->observacoes,
                situacao: $diaria->situacao,
                sourceId: $diaria->id,
                sourceType: DiariaTrabalho::class,
            ));
        }

        $movimentacoes = MovimentacaoFinanceira::query()
            ->with(['acolhido', 'diaria.empresaParceira'])
            ->where('acolhido_id', $acolhidoId)
            ->when($filters['tipo_movimentacao'] ?? null, function (Builder $query, string $tipo): void {
                if ($tipo === 'Diária') {
                    $query->whereNotNull('diaria_trabalho_id');
                    return;
                }

                $map = [
                    'Saque' => 'saque',
                    'Compra Interna' => 'compra_interna',
                    'Transferência Família' => 'transferencia_familia',
                    'Ajuste' => 'ajuste',
                    'Desconto' => 'desconto',
                ];

                if (isset($map[$tipo])) {
                    $query->where('tipo', $map[$tipo]);
                }
            })
            ->when($filters['situacao'] ?? null, fn (Builder $query, string $situacao): Builder => $query->where('meta->situacao', $situacao))
            ->when($filters['empresa_id'] ?? null, function (Builder $query, int $empresaId): void {
                $query->whereHas('diaria.empresaParceira', fn (Builder $empresaQuery): Builder => $empresaQuery->whereKey($empresaId));
            })
            ->when($filters['search'] ?? null, function (Builder $query, string $search): void {
                $like = '%' . trim($search) . '%';
                $query->where(function (Builder $builder) use ($like): void {
                    $builder->where('descricao', 'like', $like)
                        ->orWhere('tipo', 'like', $like)
                        ->orWhere('observacoes', 'like', $like)
                        ->orWhere('meta->responsavel', 'like', $like)
                        ->orWhereHas('diaria.empresaParceira', fn (Builder $empresaQuery): Builder => $empresaQuery->where('nome', 'like', $like));
                });
            })
            ->orderBy('created_at')
            ->get();

        foreach ($movimentacoes as $movimentacao) {
            $entries->push(new ExtratoFinanceiroEntryData(
                data: CarbonImmutable::parse($movimentacao->created_at ?? $movimentacao->updated_at ?? now()),
                tipo: $this->normalizeTipo($movimentacao->tipo),
                descricao: $movimentacao->descricao,
                empresa: $movimentacao->diaria?->empresaParceira?->nome,
                credito: $this->isCredit($movimentacao->tipo) ? (float) $movimentacao->valor : 0.0,
                debito: $this->isCredit($movimentacao->tipo) ? 0.0 : (float) $movimentacao->valor,
                saldoAposLancamento: 0.0,
                responsavel: $movimentacao->meta['responsavel'] ?? null,
                observacoes: $movimentacao->meta['observacoes'] ?? null,
                situacao: $movimentacao->meta['situacao'] ?? 'confirmado',
                sourceId: $movimentacao->id,
                sourceType: MovimentacaoFinanceira::class,
            ));
        }

        $entries = $entries
            ->sortBy(fn (ExtratoFinanceiroEntryData $entry): string => $entry->data->format('Y-m-d H:i:s') . '-' . str_pad((string) ($entry->sourceId ?? 0), 10, '0', STR_PAD_LEFT))
            ->values();

        $running = 0.0;
        $entries = $entries->map(function (ExtratoFinanceiroEntryData $entry) use (&$running): ExtratoFinanceiroEntryData {
            $running += $entry->credito;
            $running -= $entry->debito;

            return new ExtratoFinanceiroEntryData(
                data: $entry->data,
                tipo: $entry->tipo,
                descricao: $entry->descricao,
                empresa: $entry->empresa,
                credito: $entry->credito,
                debito: $entry->debito,
                saldoAposLancamento: $running,
                responsavel: $entry->responsavel,
                observacoes: $entry->observacoes,
                situacao: $entry->situacao,
                sourceId: $entry->sourceId,
                sourceType: $entry->sourceType,
            );
        });

        return $entries;
    }

    /**
     * @return array<string, mixed>
     */
    public function summarize(Collection $entries): array
    {
        $last = $entries->last();

        $credito = (float) $entries->sum('credito');
        $debito = (float) $entries->sum('debito');

        return [
            'saldo_atual' => $last?->saldoAposLancamento ?? 0.0,
            'total_recebido' => $credito,
            'total_sacado' => (float) $entries->where('tipo', 'Saque')->sum('debito'),
            'compras_internas' => (float) $entries->where('tipo', 'Compra Interna')->sum('debito'),
            'transferencias_familia' => (float) $entries->where('tipo', 'Transferência Família')->sum('debito'),
            'retido_cerape' => (float) $entries->where('tipo', 'Diaria')->sum(fn (ExtratoFinanceiroEntryData $entry): float => max(0, $entry->credito * 0.15)),
            'saldo_disponivel' => max(0.0, $credito - $debito),
            'ultima_movimentacao' => $last?->data?->format('d/m/Y H:i') ?? '-',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function emptySummary(): array
    {
        return [
            'saldo_atual' => 0.0,
            'total_recebido' => 0.0,
            'total_sacado' => 0.0,
            'compras_internas' => 0.0,
            'transferencias_familia' => 0.0,
            'retido_cerape' => 0.0,
            'saldo_disponivel' => 0.0,
            'ultima_movimentacao' => '-',
        ];
    }

    private function isCredit(string $tipo): bool
    {
        return in_array($tipo, ['credit', 'credito', 'credito', 'diaria', 'diária'], true);
    }

    private function normalizeTipo(string $tipo): string
    {
        return match (strtolower($tipo)) {
            'credit', 'credito', 'credito' => 'Crédito',
            'debit', 'debito' => 'Débito',
            'saque' => 'Saque',
            'compra_interna' => 'Compra Interna',
            'transferencia_familia' => 'Transferência Família',
            'ajuste' => 'Ajuste',
            'desconto' => 'Desconto',
            default => 'Movimentação',
        };
    }

    private function applyCommonFilters(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['data_inicial'] ?? null, fn (Builder $q, string $date): Builder => $q->whereDate('data', '>=', $date))
            ->when($filters['data_final'] ?? null, fn (Builder $q, string $date): Builder => $q->whereDate('data', '<=', $date))
            ->when($filters['empresa_id'] ?? null, fn (Builder $q, int $empresaId): Builder => $q->where('empresa_parceira_id', $empresaId))
            ->when($filters['situacao'] ?? null, fn (Builder $q, string $situacao): Builder => $q->where('situacao', $situacao))
            ->when($filters['search'] ?? null, function (Builder $q, string $search): Builder {
                $like = '%' . trim($search) . '%';

                return $q->where(function (Builder $builder) use ($like): void {
                    $builder->where('tipo_servico', 'like', $like)
                        ->orWhere('observacoes', 'like', $like)
                        ->orWhereHas('empresaParceira', fn (Builder $empresaQuery): Builder => $empresaQuery->where('nome', 'like', $like))
                        ->orWhereHas('acolhido', fn (Builder $acolhidoQuery): Builder => $acolhidoQuery->where('nome_completo_paciente', 'like', $like));
                });
            });
    }
}
