<?php

namespace Tests\Unit;

use App\Filament\Resources\Acolhidos\Schemas\AcolhidoForm;
use Tests\TestCase;

class AcolhidoFormTest extends TestCase
{
    public function test_prepare_for_persistence_clears_removed_moradia_fields_regardless_of_selection(): void
    {
        $data = [
            'user_id' => 1,
            'tem_documentacao' => true,
            'moradia_propria' => false,
            'mora_em_casa_aluguada' => true,
            'quanto_tempo_de_aluguel' => '6 meses',
            'em_qual_regiao' => 'Centro',
            'trabalha' => false,
            'tem_telefone' => false,
            'tem_meio_de_encaminhamento' => false,
            'toma_medicamento' => false,
            'exames_laboratoriais' => false,
            'tem_filhos' => false,
            'situacao' => 'acolhido',
        ];

        $prepared = AcolhidoForm::prepareForPersistence($data);

        $this->assertNull($prepared['quanto_tempo_de_aluguel']);
        $this->assertNull($prepared['em_qual_regiao']);
    }
}
