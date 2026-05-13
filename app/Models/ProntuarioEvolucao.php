<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProntuarioEvolucao extends Model
{
    protected $table = 'prontuarios_evolucao';

    protected $fillable = [
        'acolhido_id',
        'user_id',
        'data_prontuario',
        'conteudo',
    ];

    protected $casts = [
        'data_prontuario' => 'datetime',
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
