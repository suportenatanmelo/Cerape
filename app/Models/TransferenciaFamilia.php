<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferenciaFamilia extends Model
{
    protected $table = 'transferencias_familia';
    protected $fillable = ['acolhido_id', 'carteira_acolhido_id', 'nome_pessoa', 'parentesco', 'pix', 'banco', 'data', 'valor', 'observacoes'];
    protected $casts = ['data' => 'date', 'valor' => 'decimal:2'];
}
