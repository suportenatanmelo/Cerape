<?php

namespace App\Models;

use App\Support\Concerns\HasActivityLogs;
use App\Enums\SituacaoAcolhido;
use App\Support\ImageStorageNaming;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\CarteiraAcolhido;
use App\Models\CompraInterna;
use App\Models\MovimentacaoFinanceira;
use App\Models\SaqueFinanceiro;
use App\Models\TransferenciaFamilia;

class Acolhido extends Model
{
    use HasActivityLogs;

    public function activityLogModule(): ?string
    {
        return 'Acolhidos';
    }

    public function activityLogLabel(): ?string
    {
        return 'Acolhido';
    }

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
        'numero_rg',
        'numero_cpf',
        'numero_certidao_nascimento',
        'numero_certidao_casamento',
        'numero_carteira_trabalho',
        'numero_titulo_eleitor',
        'numero_nis',
        'numero_cartao_sus',
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
        'escolaridade_observacao',
        'profissao',
        'religiao',
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
        'interventor_nome_completo',
        'interventor_cpf',
        'interventor_rg',
        'interventor_exp',
        'interventor_rg_uf',
        'interventor_profissao',
        'interventor_data_nascimento',
        'interventor_residente',
        'interventor_complemento',
        'interventor_bairro',
        'interventor_cidade',
        'interventor_endereco_uf',
        'interventor_telefone_contato',
        'profissional_referencia_acolhido_instituicao',
        'situacao',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'data_nascimento' => 'date',
        'interventor_data_nascimento' => 'date',
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
        'receituario' => 'array',
        'exames_laboratoriais' => 'boolean',
        'tem_filhos' => 'boolean',
        'pensao_alimenticia' => 'boolean',
        'possui_contato_dos_filhos' => 'boolean',
        'documentos_civis' => 'array',
        'documentos_outros' => 'array',
        'situacao' => SituacaoAcolhido::class,
    ];

    protected static function booted(): void
    {
        static::saved(function (self $acolhido): void {
            ImageStorageNaming::syncStoredImage(
                $acolhido,
                'avatar',
                'acolhido-avatar',
                $acolhido->nome_completo_paciente,
            );
            ImageStorageNaming::syncStoredFile(
                $acolhido,
                'receituario',
                'acolhido-receituario',
                $acolhido->nome_completo_paciente,
            );
        });

        static::deleted(function (self $acolhido): void {
            ImageStorageNaming::removeStoredPath($acolhido->avatar);
            ImageStorageNaming::removeStoredPaths($acolhido->receituario ?? []);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function familyUsers(): HasMany
    {
        return $this->hasMany(User::class, 'acolhido_id');
    }

    public function acolhidoGalerias(): HasMany
    {
        return $this->hasMany(AcolhidoGaleria::class);
    }

    public function acolhidoGaleria(): HasOne
    {
        return $this->hasOne(AcolhidoGaleria::class)->latestOfMany();
    }

    public function acolhidoVideos(): HasMany
    {
        return $this->hasMany(AcolhidoVideo::class);
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

    public function atividadesDesenvolvidas(): HasMany
    {
        return $this->hasMany(AtividadeDesenvolvida::class);
    }

    public function demandasAcolhidos(): HasMany
    {
        return $this->hasMany(DemandaAcolhido::class);
    }

    public function prontuariosEvolucao(): HasMany
    {
        return $this->hasMany(ProntuarioEvolucao::class);
    }

    public function feedbackMessages(): HasMany
    {
        return $this->hasMany(FeedbackFamiliarMessage::class);
    }

    public function carteiraAcolhido(): HasOne
    {
        return $this->hasOne(CarteiraAcolhido::class);
    }

    public function historicoSituacoes(): HasMany
    {
        return $this->hasMany(AcolhidoHistoricoSituacao::class, 'acolhido_id');
    }

    public function isAtivo(): bool
    {
        return (bool) $this->ativo;
    }

    public function isEgresso(): bool
    {
        return $this->situacao instanceof SituacaoAcolhido
            && $this->situacao === SituacaoAcolhido::egresso;
    }

    public function isInternado(): bool
    {
        return $this->situacao instanceof SituacaoAcolhido
            && $this->situacao === SituacaoAcolhido::internado;
    }

    public function isDesistente(): bool
    {
        return $this->situacao instanceof SituacaoAcolhido
            && $this->situacao === SituacaoAcolhido::desistente;
    }

    public function movimentacoesFinanceiras(): HasMany
    {
        return $this->hasMany(MovimentacaoFinanceira::class);
    }

    public function saquesFinanceiros(): HasMany
    {
        return $this->hasMany(SaqueFinanceiro::class);
    }

    public function comprasInternas(): HasMany
    {
        return $this->hasMany(CompraInterna::class);
    }

    public function transferenciasFamilia(): HasMany
    {
        return $this->hasMany(TransferenciaFamilia::class);
    }
}

