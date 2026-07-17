<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GeradorAtividade extends Model
{
    protected $table = 'geradores_atividades';

    protected $fillable = [
        'user_id',
        'titulo',
        'data_programacao',
        'periodo_fim',
        'acolhidos_ids',
        'atividades_planejadas',
        'atividades_matutinas',
        'atividades_vespertinas',
        'observacoes',
        'status',
        'acolhido_id',
        'profissional_id',
        'data_atividade',
    ];

    protected $casts = [
        'data_programacao' => 'date',
        'periodo_fim' => 'date',
        'acolhidos_ids' => 'array',
        'atividades_planejadas' => 'array',
        'atividades_matutinas' => 'array',
        'atividades_vespertinas' => 'array',
        'data_atividade' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class);
    }

    public function profissional(): BelongsTo
    {
        return $this->belongsTo(User::class, 'profissional_id');
    }

    public function evolucao(): HasOne
    {
        return $this->hasOne(ProntuarioEvolucao::class, 'atividade_gerada_id');
    }

    public function atividadesAcolhidos(): HasMany
    {
        return $this->hasMany(AtividadeAcolhido::class, 'gerador_atividade_id');
    }

    /**
     * Retorna a demanda padrão para uma atividade pratica informada.
     * Recebe o nome da atividade (string) e retorna a string da demanda ou null se não houver mapeamento.
     *
     * @param  string|null  $atividade
     * @return string|null
     */
    public static function demandaForActivity(?string $atividade): ?string
    {
        if (blank($atividade)) {
            return null;
        }

        $normalize = static function (string $value): string {
            $value = mb_strtolower(trim($value));
            $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value;
            $value = preg_replace('/[^a-z0-9 ]+/', '', $value);

            return $value;
        };

        $map = [
            'cozinha e auxiliares' => '1. Preparo de alimentos e gerenciamento da equipe, cumprimento dos horários. 2. Responsável por manter ambientes, utensílios e equipamentos em funcionamento e higienizados.',
            'almoxarifado' => 'Abrir imediatamente após o término da partilha, distribuir ferramentas registrando o responsável, manter limpeza e organização, cobrar e registrar a devolução das ferramentas, guardar ferramentas e equipamentos e realizar inventário na troca da terapia semanal.',
            'limpeza e manutencao das estruturas' => 'Manter limpos os ambientes, igreja e banheiros.',
            'limpeza das estruturas dormitorios' => 'Manter limpos os ambientes, igreja e banheiros.',
            'limpeza externa capela' => 'Manter limpos os ambientes conforme orientação dos monitores.',
            'cuidado com animais' => 'Alimentação e cuidados dos animais: cachorros, gatos, patos, pavões e coelhos.',
            'projeto recicla cerape' => 'Separação, organização e reciclagem para venda de resíduos, sucatas, papel, plástico e demais materiais.',
            'projeto viveirocompostagemcafe' => 'Cultivo, plantio, manejo e cuidado das mudas, revitalização do solo, construção e manutenção de aceiros, limpeza e manutenção dos ambientes, produção de compostagem e atividades do café.',
            'projeto viveiro' => 'Cultivo, plantio, manejo e cuidado das mudas, revitalização do solo, construção e manutenção de aceiros, limpeza e manutenção dos ambientes, produção de compostagem e atividades do café.',
            'projeto compostagem' => 'Cultivo, plantio, manejo e cuidado das mudas, revitalização do solo, construção e manutenção de aceiros, limpeza e manutenção dos ambientes, produção da compostagem e atividades do café.',
            'projeto cafe' => 'Cultivo, plantio, manejo e cuidado das mudas, revitalização do solo, construção e manutenção de aceiros, limpeza e manutenção dos ambientes, produção da compostagem e atividades do café.',
            'projeto avicultura' => 'Iniciar às 06:00, fornecer água e ração, coletar ovos, controlar luz e temperatura dos pintinhos, verificar pragas e animais doentes, tratar e limpar a pocilga, preparar alimentação complementar e realizar varredura do composto.',
            'projeto ovelha' => 'Visitar o aprisco antes das 07:00, verificar água e rebanho, soltar para pastejo, fornecer ração, preparar alimentação complementar, cuidar das crias, limpar o ambiente, cuidar também do cavalo e utilizar o cão Sansão no manejo.',
            'projeto cavalo' => 'Visitar o aprisco antes das 07:00, verificar água e rebanho, soltar para pastejo, fornecer ração, preparar alimentação complementar, cuidar das crias, limpar o ambiente, cuidar também do cavalo e utilizar o cão Sansão no manejo.',
            'projeto apicultura' => 'Visitar diariamente o apiário, alimentar quando necessário, verificar caixas e quadros, limpar ao redor das colmeias, verificar traças e realizar capturas quando necessário.',
            'construcao ou reforma' => 'Executar manutenção predial, reparos em piscina, banheiros e demais estruturas.',
            'patogeno' => 'Realizar limpeza, poda, aceiros, retirada de mudas das bananeiras e transferência dos patos.',
            'lan house' => 'Realizar manutenção dos computadores, limpeza do ambiente, organização da escola de usuários e finalizar o sistema.',
            'lavanderia' => 'Atender dormitórios, salas e escritórios.',
            'marcenaria' => 'Conserto e fabricação de móveis, organização e limpeza do ambiente.',
            'revitalizacao' => 'Cuidar dos jardins, flores, mesas, bancos e limpeza dos ambientes de convivência.',
            'lideranca' => 'Acompanhar o cumprimento das regras do dormitório, refeitório, entretenimento, lavanderia, revistas e demais atividades, prover água e lenha e prestar contas ao monitor.',
        ];

        $key = $normalize($atividade);

        return $map[$key] ?? null;
    }
}
