<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemandaAcolhido extends Model
{
    protected $table = 'demandas_acolhidos';

    protected $fillable = [
        'acolhido_id',
        'demanda',
        'observacoes',
        'saida_prevista_em',
        'retorno_previsto_em',
    ];

    protected $casts = [
        'saida_prevista_em' => 'datetime',
        'retorno_previsto_em' => 'datetime',
    ];

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class);
    }
}
