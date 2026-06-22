<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactLead extends Model
{
    protected $fillable = [
        'nome',
        'telefone',
        'email',
        'mensagem',
        'respondido',
    ];

    protected $casts = [
        'respondido' => 'boolean',
    ];
}
