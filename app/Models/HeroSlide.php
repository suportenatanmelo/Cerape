<?php

namespace App\Models;

use App\Support\ImageStorageNaming;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HeroSlide extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image_path',
        'cta_label',
        'cta_url',
        'show_buttons',
        'position',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_buttons' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $slide): void {
            ImageStorageNaming::syncStoredImage($slide, 'image_path', 'galeria', $slide->title);
        });
    }

    public function imageUrl(): ?string
    {
        if (! filled($this->image_path)) {
            return null;
        }

        return $this->normalizeMediaUrl($this->image_path);
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
