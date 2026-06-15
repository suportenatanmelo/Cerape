<?php

namespace App\Models;

use App\Models\Acolhido;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcolhidoDesligamento extends Model
{
    protected $fillable = [
        'acolhido_id',
        'motivo_desligamento',
        'endereco_saida',
        'destino_saida',
        'parentes',
        'amigos',
        'retorno_rua',
        'reclamacao',
        'elogio',
    ];

    protected $casts = [
        'parentes' => 'boolean',
        'amigos' => 'boolean',
        'retorno_rua' => 'boolean',
    ];

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class);
    }
}