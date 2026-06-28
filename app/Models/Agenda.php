<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Agenda extends Model
{
    protected $fillable = [
        'acolhido_id',
        'funcionario_id',
        'titulo',
        'descricao',
        'data',
        'hora_inicio',
        'hora_fim',
        'tipo',
        'status',
        'cor',
        'dia_todo',
        'notificar',
    ];

    protected $casts = [
        'data' => 'date',
        'dia_todo' => 'boolean',
        'notificar' => 'boolean',
    ];

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class);
    }

    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'funcionario_id');
    }
}
