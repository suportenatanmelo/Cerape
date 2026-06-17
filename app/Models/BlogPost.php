<?php

namespace App\Models;

use App\Support\ImageStorageNaming;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'content',
        'cover_image',
        'cover_image_alt',
        'author_id',
        'author_name',
        'status',
        'is_featured',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (BlogPost $post): void {
            if (filled($post->title) && blank($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });

        static::saved(function (BlogPost $post): void {
            ImageStorageNaming::syncStoredImage($post, 'cover_image', 'frontend/blog/posts', $post->title);
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
