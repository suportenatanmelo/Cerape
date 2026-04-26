<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acolhido extends Model
{
    protected $fillable = [
        'user_id',
        'avatar',
        'nome_completo_paciente',
        'tem_documentacao',
        'data_nascimento',
        'estado_civil',
        'nome_do_conjuge',
        'nome_da_mae',
        'nome_do_pai',
        'tem_documentacao',
        'razao_caso_nao_tenha_documentacao',
        'quais_documentacao',
        'outros_documentacao',
        'CEP',
        'endereco_paciente',
        'bairro_do_paciente',
        'municipio_do_paciente',
        'municipio',
        'uf_municipio_do_paciente',
        'moradia_propria',
        'mora_em_casa_aluguada',
        'quanto_tempo_de_aluguel',
        'em_qual_regiao',
        'cor_da_pele',
        'trabalha',
        'nome_da_empresa_que_trabalha',
        'escolaridade',
        'profissao',
        'tem_telefone',
        'numero_do_telefone',
        'tem_meio_de_encaminhamento',
        'meio_de_encaminhamento',
        'outro_meio_de_encaminhamento_qual',
        'indicacao',
        'toma_medicamento',
        'tem_receituario',
        'receituario',
        'exames_laboratoriais',
        'tem_filhos',
        'quem_responsavel_criancas',
        'quant_filhos',
        'qual_o_nome_dos_filhos',
        'numero_telefone_filhos',
        'pensao_alimenticia',
        'possui_contato_dos_filhos',
        'responsavel_pela_intervencao_do_acolhido',
        'profissional_referencia_acolhido_instituicao',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        "tem_documentacao" => 'boolean',
        'moradia_propria' => 'boolean',
        'mora_em_casa_aluguada' => 'boolean',
        'trabalha' => 'boolean',
        'tem_telefone' => 'boolean',
        'tem_meio_de_encaminhamento' => 'boolean',
        'meio_de_encaminhamento' => 'array',
        'toma_medicamento' => 'boolean',
        'tem_receituario' => 'boolean',
        'qual_sao_as_medicacao' => 'array',
        'tem_filhos' => 'boolean',
        'pensao_alimenticia' => 'boolean',
        'possui_contato_dos_filhos' => 'boolean',

        'exames_laboratoriais' => 'array',

        //------------------------------------
        'documentos_civis' => 'array',
        'documentos_outros' => 'array',


    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
