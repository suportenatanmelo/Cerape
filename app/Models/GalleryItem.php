<?php

namespace App\Models;

use App\Support\ImageStorageNaming;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage as StorageFacade;
use Illuminate\Support\Facades\Storage;

class GalleryItem extends Model
{
    protected $fillable = [
        'gallery_category_id',
        'title',
        'caption',
        'image_path',
        'position',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $item): void {
            ImageStorageNaming::syncStoredImage($item, 'image_path', 'galeria', $item->title);
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(GalleryCategory::class, 'gallery_category_id');
    }

    public function imageUrl(): ?string
    {
        if (! filled($this->image_path)) {
            return null;
        }

        $path = trim($this->image_path);

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

        return StorageFacade::disk('public')->url($path);
    }
}
