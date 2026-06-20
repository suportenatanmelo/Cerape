<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactLead extends Model
{
    protected $fillable = [
        'nome',
        'telefone',
        'mensagem',
        'respondido',
    ];

    protected $casts = [
        'respondido' => 'boolean',
    ];
}
