<?php

namespace App\Observers;

use App\Models\CarteiraAcolhido;
use App\Models\DiariaTrabalho;
use App\Models\MovimentacaoFinanceira;
use Illuminate\Support\Facades\DB;

class DiariaTrabalhoObserver
{
    public function saving(DiariaTrabalho $diaria): void
    {
        $valorTotal = round(((float) $diaria->valor_diaria) * max(1, (int) $diaria->quantidade_dias), 2);

        $diaria->valor_total = $valorTotal;
        // O rateio continua existindo, mas a interface vai expor isso como "desconto logístico".
        $diaria->valor_cerape = round($valorTotal * 0.15, 2);
        $diaria->valor_acolhido = round($valorTotal * 0.85, 2);
    }

    public function saved(DiariaTrabalho $diaria): void
    {
        if ($diaria->situacao !== 'pago') {
            return;
        }

        if ($diaria->wasRecentlyCreated === false && $diaria->getOriginal('situacao') === 'pago') {
            return;
        }

        DB::transaction(function () use ($diaria): void {
            $carteira = CarteiraAcolhido::query()->firstOrCreate(
                ['acolhido_id' => $diaria->acolhido_id],
                [
                    'saldo_atual' => 0,
                    'total_recebido' => 0,
                    'total_sacado' => 0,
                    'total_retido_instituicao' => 0,
                ],
            );

            $saldoAnterior = (float) $carteira->saldo_atual;
            $carteira->saldo_atual = round($saldoAnterior + (float) $diaria->valor_acolhido, 2);
            $carteira->total_recebido = round((float) $carteira->total_recebido + (float) $diaria->valor_total, 2);
            $carteira->total_retido_instituicao = round((float) $carteira->total_retido_instituicao + (float) $diaria->valor_cerape, 2);
            $carteira->save();

            MovimentacaoFinanceira::query()->create([
                'acolhido_id' => $diaria->acolhido_id,
                'diaria_trabalho_id' => $diaria->getKey(),
                'tipo' => 'credito',
                'valor' => $diaria->valor_acolhido,
                'saldo_anterior' => $saldoAnterior,
                'saldo_posterior' => $carteira->saldo_atual,
                'descricao' => 'Crédito de diária de trabalho',
                'meta' => [
                    'empresa' => $diaria->empresaParceira?->nome,
                    'frente' => $diaria->frenteTrabalho?->nome,
                    'valor_total' => $diaria->valor_total,
                    'valor_desconto_logistico' => $diaria->valor_cerape,
                ],
            ]);
        });
    }
}
