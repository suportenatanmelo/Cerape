<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvaliacaoPessoal extends Model
{
    protected $fillable = [
        'acolhido_id',
        'user_id',
        'dias_na_casa',
        'controler',
        'autonomia',
        'transparencia',
        'superacao',
        'autocuidado',
        'Total',
    ];

    protected $casts = [
        'controler' => 'decimal:2',
        'autonomia' => 'decimal:2',
        'transparencia' => 'decimal:2',
        'superacao' => 'decimal:2',
        'autocuidado' => 'decimal:2',
        'Total' => 'decimal:2',
    ];

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
