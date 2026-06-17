<?php

namespace App\Models;

use App\Support\ImageStorageNaming;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FrontendTestimonial extends Model
{
    protected $fillable = [
        'name',
        'role',
        'summary',
        'image',
        'image_alt',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $testimonial): void {
            ImageStorageNaming::syncStoredImage($testimonial, 'image', 'frontend/testimonials', $testimonial->name);
        });
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
