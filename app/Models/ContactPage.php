<?php

namespace App\Models;

use App\Support\ImageStorageNaming;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ContactPage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'subtitle',
        'intro',
        'hero_image',
        'hero_image_alt',
        'email',
        'phone',
        'whatsapp',
        'address',
        'opening_hours',
        'map_embed_code',
        'map_embed_url',
        'cta_label',
        'cta_url',
        'social_links',
        'is_active',
    ];

    protected $casts = [
        'social_links' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (ContactPage $page): void {
            if (filled($page->title) && blank($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });

        static::saved(function (ContactPage $page): void {
            ImageStorageNaming::syncStoredImage($page, 'hero_image', 'frontend/contact', $page->title);
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
