<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarteiraAcolhido extends Model
{
    protected $table = 'carteiras_acolhidos';

    protected $fillable = [
        'acolhido_id',
        'saldo_atual',
        'total_recebido',
        'total_sacado',
        'total_retido_instituicao',
    ];

    protected $casts = [
        'saldo_atual' => 'decimal:2',
        'total_recebido' => 'decimal:2',
        'total_sacado' => 'decimal:2',
        'total_retido_instituicao' => 'decimal:2',
    ];

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class);
    }

    public function movimentacoes(): HasMany
    {
        return $this->hasMany(MovimentacaoFinanceira::class, 'acolhido_id', 'acolhido_id');
    }
}
