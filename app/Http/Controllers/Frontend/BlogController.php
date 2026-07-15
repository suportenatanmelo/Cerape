<?php

namespace App\Http\Controllers\Frontend;

use App\Models\BlogPost;
use App\Models\FrontendSetting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BlogController extends Controller
{
    public function index()
    {
        return view('frontend.blog-list', [
            'settings' => FrontendSetting::query()->first(),
            'posts' => BlogPost::query()->visible()->where('show_in_blog', true)->orderByDesc('published_at')->orderBy('position')->paginate(9),
        ]);
    }

    public function show(string $slug)
    {
        $settings = FrontendSetting::query()->first();
        $post = BlogPost::query()->visible()->where('slug', $slug)->where('show_in_blog', true)->firstOrFail();

        return view('frontend.blog-post', [
            'settings' => $settings,
            'post' => $post,
        ]);
    }
}
