<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PillarCard extends Model
{
    protected $fillable = [
        'title',
        'summary',
        'position',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
