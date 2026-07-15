<?php

declare(strict_types=1);

namespace App\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Seo extends Model
{
    protected $table = 'cms_seo';

    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical',
        'open_graph',
        'model_type',
        'model_id',
        'active',
    ];

    protected $casts = [
        'open_graph' => 'array',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
