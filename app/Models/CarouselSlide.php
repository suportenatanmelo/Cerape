<?php

namespace App\Models;

use App\Support\ImageStorageNaming;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CarouselSlide extends Model
{
    protected $fillable = [
        'slug',
        'eyebrow',
        'eyebrow_color',
        'title',
        'title_color',
        'description',
        'description_color',
        'image',
        'image_alt',
        'cta_label',
        'cta_text_color',
        'cta_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (CarouselSlide $slide): void {
            if (filled($slide->title) && blank($slide->slug)) {
                $slide->slug = Str::slug($slide->title);
            }
        });

        static::saved(function (CarouselSlide $slide): void {
            ImageStorageNaming::syncStoredImage($slide, 'image', 'frontend/carousel', $slide->title);
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

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
