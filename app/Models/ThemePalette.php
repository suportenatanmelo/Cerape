<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemePalette extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'primary_color',
        'secondary_color',
        'surface_color',
        'background_color',
        'text_color',
        'accent_color',
        'success_color',
        'warning_color',
        'danger_color',
        'info_color',
        'header_color',
        'footer_color',
        'button_color',
        'link_color',
        'card_color',
        'border_color',
        'hover_color',
        'focus_color',
        'dark_background_color',
        'dark_surface_color',
        'is_active',
        'is_current',
        'position',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_current' => 'boolean',
    ];

    public function activate(): void
    {
        static::query()->whereKeyNot($this->getKey())->update([
            'is_active' => false,
            'is_current' => false,
        ]);

        $this->forceFill([
            'is_active' => true,
            'is_current' => true,
        ])->save();
    }
}
