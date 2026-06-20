<?php

namespace App\Models;

use App\Support\ImageStorageNaming;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class GalleryCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image_path',
        'position',
        'show_on_home',
        'show_in_menu',
        'active',
    ];

    protected $casts = [
        'show_on_home' => 'boolean',
        'show_in_menu' => 'boolean',
        'active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $category): void {
            ImageStorageNaming::syncStoredImage($category, 'image_path', 'galeria', $category->name);
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(GalleryItem::class, 'gallery_category_id');
    }

    public function imageUrl(): ?string
    {
        if (! filled($this->image_path)) {
            return null;
        }

        $path = trim((string) $this->image_path);

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        $path = ltrim(str_starts_with($path, 'storage/') ? substr($path, 8) : $path, '/');

        return Storage::disk('public')->url($path);
    }
}
