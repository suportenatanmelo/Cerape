<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AtividadeAcolhido extends Model
{
    protected $table = 'atividades_acolhidos';

    protected $fillable = [
        'gerador_atividade_id',
        'acolhido_id',
        'atividade',
        'demanda',
        'data_programacao',
        'usuario_id',
        'status',
    ];

    protected $casts = [
        'data_programacao' => 'date',
    ];

    public function geradorAtividade(): BelongsTo
    {
        return $this->belongsTo(GeradorAtividade::class, 'gerador_atividade_id');
    }

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
