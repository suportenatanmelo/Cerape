<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\CarouselSlide;
use App\Models\ContactPage;
use App\Models\Home;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('frontend.layout', $this->pageData([
            'blogPosts' => $this->publishedPosts(limit: 3),
        ]));
    }

    public function about(): View
    {
        return view('frontend.about', $this->pageData([
            'blogPosts' => $this->publishedPosts(limit: 3),
        ]));
    }

    public function blog(): View
    {
        $blogPosts = collect();

        if ($this->hasBlogPostsTable()) {
            $blogPosts = BlogPost::query()
                ->published()
                ->latest('published_at')
                ->latest('id')
                ->paginate(6);
        }

        return view('frontend.blog.index', $this->pageData([
            'blogPosts' => $blogPosts,
        ]));
    }

    public function show(string $slug): View
    {
        abort_unless($this->hasBlogPostsTable(), 404);

        $post = BlogPost::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('frontend.blog.show', $this->pageData([
            'post' => $post,
            'relatedPosts' => BlogPost::query()
                ->published()
                ->whereKeyNot($post->getKey())
                ->latest('published_at')
                ->latest('id')
                ->limit(3)
                ->get(),
        ]));
    }

    public function contact(): View
    {
        return view('frontend.contact', $this->pageData([
            'contactPage' => $this->contactPage(),
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    private function pageData(array $extra = []): array
    {
        return array_merge([
            'home' => Home::query()->latest('id')->first(),
            'contactPage' => $this->contactPage(),
            'carouselSlides' => $this->carouselSlides(),
        ], $extra);
    }

    /**
     * @return \Illuminate\Support\Collection<int, BlogPost>
     */
    private function publishedPosts(int $limit = 3): Collection
    {
        if (! $this->hasBlogPostsTable()) {
            return collect();
        }

        return BlogPost::query()
            ->published()
            ->latest('published_at')
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    private function hasBlogPostsTable(): bool
    {
        return Schema::hasTable('blog_posts');
    }

    /**
     * @return \Illuminate\Support\Collection<int, CarouselSlide|array<string, mixed>>
     */
    private function carouselSlides(): Collection
    {
        if ($this->hasCarouselSlidesTable()) {
            $slides = CarouselSlide::query()
                ->active()
                ->ordered()
                ->get();

            if ($slides->isNotEmpty()) {
                return $slides;
            }
        }

        return collect(Home::query()->latest('id')->first()?->carousel_items ?? []);
    }

    private function contactPage(): ?ContactPage
    {
        if (! $this->hasContactPagesTable()) {
            return null;
        }

        return ContactPage::query()
            ->active()
            ->latest('id')
            ->first();
    }

    private function hasCarouselSlidesTable(): bool
    {
        return Schema::hasTable('carousel_slides');
    }

    private function hasContactPagesTable(): bool
    {
        return Schema::hasTable('contact_pages');
    }
}
