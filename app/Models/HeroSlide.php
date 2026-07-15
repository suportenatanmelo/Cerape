<?php

namespace App\Models;

use App\Support\ImageStorageNaming;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HeroSlide extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image_path',
        'mobile_image_path',
        'cta_label',
        'cta_url',
        'secondary_cta_label',
        'secondary_cta_url',
        'text_color',
        'alignment',
        'overlay_color',
        'overlay_opacity',
        'show_buttons',
        'position',
        'is_active',
        'hidden',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_buttons' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'hidden' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $slide): void {
            ImageStorageNaming::syncStoredImage($slide, 'image_path', 'galeria', $slide->title);
            ImageStorageNaming::syncStoredImage($slide, 'mobile_image_path', 'galeria', $slide->title . ' mobile');
        });
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('hidden', false);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->visible()
            ->where('is_active', true)
            ->where(function (Builder $query): void {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query): void {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function imageUrl(): ?string
    {
        if (! filled($this->image_path)) {
            return null;
        }

        return $this->normalizeMediaUrl($this->image_path);
    }

    public function mobileImageUrl(): ?string
    {
        if (! filled($this->mobile_image_path)) {
            return null;
        }

        return $this->normalizeMediaUrl($this->mobile_image_path);
    }

    protected function normalizeMediaUrl(string $path): string
    {
        $path = trim($path);

        if (preg_match('#^https?://#i', $path)) {
            $parsed = parse_url($path);

            if (is_array($parsed) && filled($parsed['path'] ?? null)) {
                $normalized = ltrim((string) $parsed['path'], '/');

                return str_starts_with($normalized, 'storage/')
                    ? '/' . $normalized
                    : '/storage/' . $normalized;
            }

            return $path;
        }

        $path = ltrim($path, '/');

        $path = str_starts_with($path, 'storage/') ? substr($path, 8) : $path;

        return Storage::disk('public')->url($path);
    }
}
