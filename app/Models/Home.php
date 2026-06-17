<?php

namespace App\Models;

use App\Support\ImageStorageNaming;
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
        'testimonials',
        'enable_carousel',
        'carousel_items',
        'feature_cards',
        'treatment_cards',
    ];

    protected $casts = [
        'testimonials' => 'array',
        'enable_carousel' => 'boolean',
        'carousel_items' => 'array',
        'feature_cards' => 'array',
        'treatment_cards' => 'array',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $home): void {
            ImageStorageNaming::syncStoredImage($home, 'hero_image', 'frontend/homes/hero', $home->title ?: 'home');
            ImageStorageNaming::syncStoredImage($home, 'about_image', 'frontend/homes/about', $home->about_title ?: 'sobre');
            ImageStorageNaming::syncStoredImage($home, 'projects_image', 'frontend/homes/projects', $home->projects_title ?: 'projetos');
            ImageStorageNaming::syncStoredImage($home, 'signup_image', 'frontend/homes/signup', $home->signup_title ?: 'chamada');
        });
    }
}
