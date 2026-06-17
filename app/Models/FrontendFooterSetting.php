<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FrontendFooterSetting extends Model
{
    protected $fillable = [
        'brand_name',
        'tagline',
        'address',
        'email',
        'phone',
        'whatsapp',
        'map_embed_code',
        'map_embed_url',
        'quick_links',
        'social_links',
        'copyright_text',
        'use_theme_colors',
        'background_color',
        'text_color',
        'muted_color',
        'border_color',
        'is_active',
    ];

    protected $casts = [
        'quick_links' => 'array',
        'social_links' => 'array',
        'use_theme_colors' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $setting): void {
            if ($setting->is_active) {
                static::query()
                    ->when($setting->exists, fn ($query) => $query->whereKeyNot($setting->getKey()))
                    ->update(['is_active' => false]);
            }
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
