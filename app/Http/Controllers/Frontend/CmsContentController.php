<?php

namespace App\Http\Controllers\Frontend;

use App\Models\CmsContent;
use App\Models\FrontendSetting;
use Illuminate\Routing\Controller;

class CmsContentController extends Controller
{
    public function index(string $type)
    {
        $title = match ($type) {
            CmsContent::TYPE_NEWS => 'Notícias',
            CmsContent::TYPE_EVENT => 'Eventos',
            CmsContent::TYPE_FAQ => 'Perguntas frequentes',
            default => 'Conteúdo',
        };

        return view('frontend.cms-list', [
            'settings' => FrontendSetting::query()->first(),
            'title' => $title,
            'items' => CmsContent::query()->type($type)->published()->orderByDesc('starts_at')->orderBy('position')->paginate(10),
            'contentType' => $type,
        ]);
    }

    public function show(string $type, string $slug)
    {
        $item = CmsContent::query()->type($type)->where('slug', $slug)->published()->firstOrFail();

        return view('frontend.cms-detail', [
            'settings' => FrontendSetting::query()->first(),
            'item' => $item,
        ]);
    }
}
