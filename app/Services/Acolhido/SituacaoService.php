<?php

declare(strict_types=1);

namespace App\Services\Acolhido;

use App\Enums\SituacaoAcolhido;
use App\Models\Acolhido;
use App\Models\AcolhidoHistoricoSituacao;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\DB;
use Throwable;

final class SituacaoService
{
    public function __construct(private readonly ActivityLogger $activityLogger)
    {
    }

    public function changeSituacao(Acolhido $acolhido, SituacaoAcolhido $nova, ?string $observacao, ?User $usuario): void
    {
        DB::beginTransaction();

        try {
            $anterior = $acolhido->situacao instanceof SituacaoAcolhido ? $acolhido->situacao->value : (string) ($acolhido->situacao ?? '');

            $acolhido->situacao = $nova;
            $acolhido->save();

            AcolhidoHistoricoSituacao::create([
                'acolhido_id' => $acolhido->getKey(),
                'usuario_id' => $usuario?->getAuthIdentifier(),
                'situacao_anterior' => $anterior,
                'situacao_nova' => $nova->value,
                'observacao' => $observacao,
                'created_at' => now(),
            ]);

            // Auditoria
            $this->activityLogger->custom(
                'Acolhidos',
                'alterar_situacao',
                "Alterou situação de {$acolhido->nome_completo_paciente}",
                $acolhido,
                ['situacao' => $anterior],
                ['situacao' => $nova->value],
            );

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
