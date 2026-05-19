<?php

namespace App\Models;

use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CuratorMedia extends Media
{
    protected $fillable = [
        'disk',
        'directory',
        'visibility',
        'name',
        'path',
        'width',
        'height',
        'size',
        'type',
        'ext',
        'alt',
        'title',
        'description',
        'caption',
        'exif',
        'curations',
        'file',
        'acolhido_id',
    ];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
        'size' => 'integer',
        'curations' => 'array',
        'exif' => 'array',
        'acolhido_id' => 'integer',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('family_gallery_visibility', function (Builder $query): void {
            $acolhidoId = auth()->user()?->linkedAcolhidoId();

            if ($acolhidoId !== null) {
                $query->where('acolhido_id', $acolhidoId);
            }
        });

        static::creating(function (self $media): void {
            $media->acolhido_id ??= auth()->user()?->linkedAcolhidoId();
        });
    }

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class);
    }
}
