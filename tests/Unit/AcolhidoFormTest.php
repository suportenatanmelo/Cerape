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

    public function test_prepare_for_persistence_clears_address_fields_when_both_moradia_options_are_false(): void
    {
        $data = [
            'user_id' => 1,
            'tem_documentacao' => false,
            'moradia_propria' => false,
            'mora_em_casa_aluguada' => false,
            'endereco_paciente' => 'Rua Teste',
            'bairro_do_paciente' => 'Centro',
            'municipio_do_paciente' => 'São Paulo',
            'uf_municipio_do_paciente' => 'SP',
            'trabalha' => false,
            'tem_telefone' => false,
            'tem_meio_de_encaminhamento' => false,
            'toma_medicamento' => false,
            'exames_laboratoriais' => false,
            'tem_filhos' => false,
            'situacao' => 'acolhido',
        ];

        $prepared = AcolhidoForm::prepareForPersistence($data);

        $this->assertNull($prepared['endereco_paciente']);
        $this->assertNull($prepared['bairro_do_paciente']);
        $this->assertNull($prepared['municipio_do_paciente']);
        $this->assertNull($prepared['uf_municipio_do_paciente']);
    }
}
