<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompraInterna extends Model
{
    protected $table = 'compras_internas';
    protected $fillable = ['acolhido_id', 'carteira_acolhido_id', 'categoria', 'data', 'valor', 'responsavel', 'observacoes'];
    protected $casts = ['data' => 'date', 'valor' => 'decimal:2'];
}
