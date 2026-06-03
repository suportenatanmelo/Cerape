<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Casts\CommaSeparatedString;

class ProntuarioEvolucao extends Model
{
    protected $table = 'prontuarios_evolucao';

    protected $fillable = [
        'acolhido_id',
        'user_id',
        'funcao_responsavel_informacao',
        'atividade',
        'nota_elogio',
        'data_prontuario',
        'proxima_data_prontuario',
        'conteudo',
    ];

    protected $casts = [
        'data_prontuario' => 'datetime',
        'proxima_data_prontuario' => 'datetime',
        'atividade' => CommaSeparatedString::class,
        'nota_elogio' => 'integer',
    ];

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
