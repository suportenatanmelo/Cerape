<?php

namespace App\Models;

use App\Support\FrontendThemePresets;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FrontendThemeProfile extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'description',
        'preset_key',
        'primary_color',
        'secondary_color',
        'accent_color',
        'background_color',
        'surface_color',
        'surface_strong_color',
        'ink_color',
        'text_color',
        'muted_color',
        'body_font',
        'display_font',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $profile): void {
            if (filled($profile->name) && blank($profile->slug)) {
                $profile->slug = Str::slug($profile->name);
            }

            if (filled($profile->preset_key) && blank($profile->description)) {
                $preset = FrontendThemePresets::profile($profile->preset_key);

                if ($preset !== null) {
                    $profile->description = $preset['description'];
                }
            }

            if ($profile->is_active) {
                static::query()
                    ->when($profile->exists, fn ($query) => $query->whereKeyNot($profile->getKey()))
                    ->update(['is_active' => false]);
            }
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
