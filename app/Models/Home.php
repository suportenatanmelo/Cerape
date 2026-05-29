<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'hero_image',
        'hero_image_alt',
        'cta_label',
        'cta_url',
        'about_title',
        'about_subtitle',
        'about_image',
        'about_image_alt',
        'projects_title',
        'projects_subtitle',
        'projects_image',
        'projects_image_alt',
        'signup_title',
        'signup_subtitle',
        'signup_image',
        'signup_image_alt',
        'enable_carousel',
        'carousel_items',
    ];

    protected $casts = [
        'enable_carousel' => 'boolean',
        'carousel_items' => 'array',
    ];
}
