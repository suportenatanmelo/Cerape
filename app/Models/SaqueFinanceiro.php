<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaqueFinanceiro extends Model
{
    protected $table = 'saques_financeiros';

    protected $fillable = [
        'acolhido_id', 'carteira_acolhido_id', 'data', 'valor', 'responsavel', 'assinatura', 'observacoes',
    ];

    protected $casts = ['data' => 'date', 'valor' => 'decimal:2'];
}
