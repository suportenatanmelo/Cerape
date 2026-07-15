<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiariaTrabalho extends Model
{
    protected $table = 'diarias_trabalho';

    protected $fillable = [
        'empresa_parceira_id',
        'acolhido_id',
        'frente_trabalho_id',
        'data',
        'tipo_servico',
        'quantidade_dias',
        'valor_diaria',
        'valor_total',
        'valor_cerape',
        'valor_acolhido',
        'situacao',
        'observacoes',
    ];

    protected $casts = [
        'data' => 'date',
        'quantidade_dias' => 'integer',
        'valor_diaria' => 'decimal:2',
        'valor_total' => 'decimal:2',
        'valor_cerape' => 'decimal:2',
        'valor_acolhido' => 'decimal:2',
    ];

    public function empresaParceira(): BelongsTo
    {
        return $this->belongsTo(EmpresaParceira::class, 'empresa_parceira_id');
    }

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class);
    }

    public function frenteTrabalho(): BelongsTo
    {
        return $this->belongsTo(FrenteTrabalho::class, 'frente_trabalho_id');
    }
}
