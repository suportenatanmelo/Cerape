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
}
