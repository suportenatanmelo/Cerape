<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Acolhido extends Model
{
    protected $fillable = [
        'user_id',
        'ativo',
        'avatar',
        'nome_completo_paciente',
        'data_nascimento',
        'estado_civil',
        'nome_do_conjuge',
        'nome_da_mae',
        'nome_do_pai',
        'tem_documentacao',
        'razao_caso_nao_tenha_documentacao',
        'documentos_civis',
        'documentos_outros',
        'CEP',
        'endereco_paciente',
        'bairro_do_paciente',
        'municipio_do_paciente',
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
        'qual_sao_as_medicacao',
        'tem_receituario',
        'receituario',
        'exames_laboratoriais',
        'outros',
        'tem_filhos',
        'quem_responsavel_criancas',
        'quantidade_filhos',
        'qual_o_nome_dos_filhos',
        'numero_telefone_filhos',
        'pensao_alimenticia',
        'possui_contato_dos_filhos',
        'responsavel_pela_intervencao_do_acolhido',
        'profissional_referencia_acolhido_instituicao',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'data_nascimento' => 'date',
        'ativo' => 'boolean',
        'tem_documentacao' => 'boolean',
        'moradia_propria' => 'boolean',
        'mora_em_casa_aluguada' => 'boolean',
        'trabalha' => 'boolean',
        'tem_telefone' => 'boolean',
        'tem_meio_de_encaminhamento' => 'boolean',
        'meio_de_encaminhamento' => 'array',
        'toma_medicamento' => 'boolean',
        'qual_sao_as_medicacao' => 'array',
        'tem_receituario' => 'boolean',
        'exames_laboratoriais' => 'boolean',
        'tem_filhos' => 'boolean',
        'pensao_alimenticia' => 'boolean',
        'possui_contato_dos_filhos' => 'boolean',
        'documentos_civis' => 'array',
        'documentos_outros' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function avaliacoesPessoais(): HasMany
    {
        return $this->hasMany(AvaliacaoPessoal::class);
    }

    public function substanciaPsicoativas(): HasMany
    {
        return $this->hasMany(SubstanciaPsicoativas::class);
    }

    public function saudes(): HasMany
    {
        return $this->hasMany(Saude::class);
    }
}
