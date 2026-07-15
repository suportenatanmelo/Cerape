<?php

declare(strict_types=1);

namespace App\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    protected $table = 'cms_media';

    protected $fillable = [
        'disk',
        'path',
        'filename',
        'mime',
        'size',
        'width',
        'height',
        'collection',
        'alt',
        'caption',
        'copyright',
        'uploaded_by',
        'active',
    ];

    protected $casts = [
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'active' => 'boolean',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'uploaded_by');
    }
}
