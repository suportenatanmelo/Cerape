<?php

namespace App\Models;

use App\Support\ImageStorageNaming;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'author_name',
        'published_at',
        'image_path',
        'tags',
        'show_on_home',
        'show_in_blog',
        'position',
        'active',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
        'show_on_home' => 'boolean',
        'show_in_blog' => 'boolean',
        'active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $post): void {
            ImageStorageNaming::syncStoredImage($post, 'image_path', 'blog', $post->title);
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
