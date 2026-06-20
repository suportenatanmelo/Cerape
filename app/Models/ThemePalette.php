<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemePalette extends Model
{
    protected $fillable = [
        'name',
        'primary_color',
        'secondary_color',
        'surface_color',
        'background_color',
        'text_color',
        'accent_color',
        'is_active',
        'position',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
