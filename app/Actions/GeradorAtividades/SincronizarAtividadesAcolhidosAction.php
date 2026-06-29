<?php

namespace App\Actions\GeradorAtividades;

use App\Models\AtividadeAcolhido;
use App\Models\GeradorAtividade;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SincronizarAtividadesAcolhidosAction
{
    public function execute(GeradorAtividade $geradorAtividade): void
    {
        DB::transaction(function () use ($geradorAtividade): void {
            AtividadeAcolhido::query()
                ->where('gerador_atividade_id', $geradorAtividade->getKey())
                ->delete();

            $atividades = collect($geradorAtividade->atividades_planejadas ?? [])
                ->filter(fn (mixed $item): bool => is_array($item))
                ->values();

            foreach ($atividades as $atividadeItem) {
                $atividade = trim((string) Arr::get($atividadeItem, 'atividade_pratica.0', ''));
                $demanda = (string) Arr::get($atividadeItem, 'demanda', '');
                $acolhidos = collect(Arr::get($atividadeItem, 'acolhidos_ids', []))
                    ->map(fn (mixed $item): string => trim((string) $item))
                    ->filter()
                    ->unique()
                    ->values();

                foreach ($acolhidos as $acolhidoNome) {
                    $acolhido = \App\Models\Acolhido::query()
                        ->where('nome_completo_paciente', $acolhidoNome)
                        ->first();

                    if (! $acolhido) {
                        continue;
                    }

                    AtividadeAcolhido::query()->create([
                        'gerador_atividade_id' => $geradorAtividade->getKey(),
                        'acolhido_id' => $acolhido->getKey(),
                        'atividade' => $atividade !== '' ? $atividade : (string) $geradorAtividade->titulo,
                        'demanda' => $demanda !== '' ? $demanda : null,
                        'data_programacao' => $geradorAtividade->data_programacao,
                        'usuario_id' => $geradorAtividade->user_id,
                        'status' => 'pendente',
                    ]);
                }
            }
        });
    }
}
