<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeradorAtividade extends Model
{
    protected $table = 'geradores_atividades';

    protected $fillable = [
        'user_id',
        'titulo',
        'data_programacao',
        'acolhidos_ids',
        'atividades_matutinas',
        'atividades_vespertinas',
        'observacoes',
    ];

    protected $casts = [
        'data_programacao' => 'date',
        'acolhidos_ids' => 'array',
        'atividades_matutinas' => 'array',
        'atividades_vespertinas' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
