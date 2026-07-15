<?php

namespace App\Providers;

use App\Cms\Models\Block;
use App\Cms\Models\Media;
use App\Cms\Models\Menu;
use App\Cms\Models\Page;
use App\Cms\Models\Seo;
use App\Policies\BlockPolicy;
use App\Policies\MediaPolicy;
use App\Policies\MenuPolicy;
use App\Policies\PagePolicy;
use App\Policies\SeoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Page::class => PagePolicy::class,
        Block::class => BlockPolicy::class,
        Menu::class => MenuPolicy::class,
        Media::class => MediaPolicy::class,
        Seo::class => SeoPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
