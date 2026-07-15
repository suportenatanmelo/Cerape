<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PillarCard extends Model
{
    protected $fillable = [
        'title',
        'summary',
        'position',
        'active',
        'hidden',
    ];

    protected $casts = [
        'active' => 'boolean',
        'hidden' => 'boolean',
    ];

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('active', true)->where('hidden', false);
    }
}
