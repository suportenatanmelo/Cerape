<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Casts\CommaSeparatedString;
use App\Models\AtividadeAcolhido;

class ProntuarioEvolucao extends Model
{
    protected $table = 'prontuarios_evolucao';

    protected $fillable = [
        'acolhido_id',
        'user_id',
        'atividade_gerada_id',
        'atividade',
        'data_prontuario',
        'proxima_data_prontuario',
        'conteudo',
    ];

    protected $casts = [
        'data_prontuario' => 'datetime',
        'proxima_data_prontuario' => 'datetime',
        'atividade' => CommaSeparatedString::class,
    ];

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class);
    }

    public function atividade(): BelongsTo
    {
        return $this->belongsTo(AtividadeAcolhido::class, 'atividade_gerada_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
