<?php

namespace App\Http\Controllers\Frontend;

use App\Cms\Models\Page;
use App\Models\FrontendSetting;
use Illuminate\Routing\Controller;

class CmsPageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::query()
            ->published()
            ->where('slug', $slug)
            ->with('blocks', 'seo')
            ->firstOrFail();

        return view('frontend.cms-page', [
            'settings' => FrontendSetting::query()->first(),
            'page' => $page,
        ]);
    }
}
