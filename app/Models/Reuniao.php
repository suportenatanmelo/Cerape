<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reuniao extends Model
{
    protected $table = 'reunioes';

    protected $fillable = [
        'user_id',
        'titulo',
        'descricao',
        'data_reuniao',
        'ata',
    ];

    protected $casts = [
        'data_reuniao' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
