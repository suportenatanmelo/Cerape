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
    ];
}
