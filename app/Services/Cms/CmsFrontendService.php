<?php

namespace App\Services\Cms;

use App\Models\CmsContent;
use App\Repositories\CmsContentRepository;

class CmsFrontendService
{
    public function __construct(private readonly CmsContentRepository $contents)
    {
    }

    public function homeData(): array
    {
        return [
            'homeBlocks' => $this->contents->publishedByType(CmsContent::TYPE_HOME_BLOCK),
            'treatments' => $this->contents->publishedByType(CmsContent::TYPE_TREATMENT),
            'news' => $this->contents->publishedByType(CmsContent::TYPE_NEWS, 6),
            'events' => $this->contents->publishedByType(CmsContent::TYPE_EVENT, 6),
            'testimonials' => $this->contents->publishedByType(CmsContent::TYPE_TESTIMONIAL),
            'partners' => $this->contents->publishedByType(CmsContent::TYPE_PARTNER),
            'faqs' => $this->contents->publishedByType(CmsContent::TYPE_FAQ),
            'banners' => $this->contents->publishedByType(CmsContent::TYPE_BANNER),
            'popups' => $this->contents->publishedByType(CmsContent::TYPE_POPUP),
            'menuItems' => $this->contents->publishedByType(CmsContent::TYPE_MENU_ITEM),
            'footerWidgets' => $this->contents->publishedByType(CmsContent::TYPE_FOOTER_WIDGET),
            'socialLinks' => $this->contents->publishedByType(CmsContent::TYPE_SOCIAL_LINK),
        ];
    }
}
