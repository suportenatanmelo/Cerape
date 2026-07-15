<?php

declare(strict_types=1);

namespace App\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class Page extends Model
{
    protected $table = 'cms_pages';

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'status',
        'is_homepage',
        'parent_id',
        'published_at',
        'settings',
        'seo_id',
    ];

    protected $casts = [
        'is_homepage' => 'boolean',
        'published_at' => 'datetime',
        'settings' => 'array',
    ];

    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class, 'page_id')->orderBy('position');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function seo(): BelongsTo
    {
        return $this->belongsTo(Seo::class, 'seo_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->where(function (Builder $query): void {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }
}
