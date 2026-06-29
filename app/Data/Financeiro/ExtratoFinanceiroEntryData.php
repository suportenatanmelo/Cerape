<?php

namespace App\Data\Financeiro;

use Carbon\CarbonInterface;

readonly class ExtratoFinanceiroEntryData
{
    public function __construct(
        public CarbonInterface $data,
        public string $tipo,
        public string $descricao,
        public ?string $empresa,
        public float $credito,
        public float $debito,
        public float $saldoAposLancamento,
        public ?string $responsavel,
        public ?string $observacoes,
        public string $situacao,
        public ?int $sourceId = null,
        public ?string $sourceType = null,
    ) {
    }
}
