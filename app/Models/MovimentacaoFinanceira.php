<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimentacaoFinanceira extends Model
{
    protected $table = 'movimentacoes_financeiras';

    protected $fillable = [
        'acolhido_id',
        'diaria_trabalho_id',
        'tipo',
        'valor',
        'saldo_anterior',
        'saldo_posterior',
        'descricao',
        'meta',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'saldo_anterior' => 'decimal:2',
        'saldo_posterior' => 'decimal:2',
        'meta' => 'array',
    ];

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class);
    }

    public function diaria(): BelongsTo
    {
        return $this->belongsTo(DiariaTrabalho::class, 'diaria_trabalho_id');
    }
}
