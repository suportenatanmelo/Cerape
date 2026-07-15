<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class AcolhidoHistoricoSituacao extends Model
{
    protected $table = 'acolhidos_historico_situacoes';

    public $timestamps = false;

    protected $fillable = [
        'acolhido_id',
        'usuario_id',
        'situacao_anterior',
        'situacao_nova',
        'observacao',
        'created_at',
    ];

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class, 'acolhido_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
