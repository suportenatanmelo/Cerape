<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlideTrash extends Model
{
    protected $table = 'hero_slide_trashes';

    protected $fillable = [
        'hero_slide_id',
        'title',
        'image_path',
        'mobile_image_path',
        'og_image_path',
        'payload',
        'deleted_by',
        'deleted_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'deleted_at' => 'datetime',
    ];
}
