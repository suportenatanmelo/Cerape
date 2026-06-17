<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\CarouselSlide;
use App\Models\ContactPage;
use App\Models\FrontendFooterSetting;
use App\Models\FrontendThemeProfile;
use App\Models\FrontendTestimonial;
use App\Models\Home;
use App\Models\User;
use App\Support\MapEmbedResolver;
use App\Support\FrontendThemePresets;
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
                ->with('author.roles')
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
            ->with('author.roles')
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('frontend.blog.show', $this->pageData([
            'post' => $post,
            'authorProfile' => $this->resolveBlogAuthor($post),
            'relatedPosts' => BlogPost::query()
                ->with('author.roles')
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
            'themeProfile' => $this->themeProfile(),
            'footerSettings' => $this->footerSettings(),
            'carouselSlides' => $this->carouselSlides(),
            'testimonials' => $this->testimonials(),
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
            ->with('author.roles')
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

    /**
     * @return \App\Models\FrontendThemeProfile|array<string, string>
     */
    private function themeProfile(): FrontendThemeProfile|array
    {
        if (Schema::hasTable('frontend_theme_profiles')) {
            $profile = FrontendThemeProfile::query()
                ->active()
                ->latest('id')
                ->first();

            if ($profile !== null) {
                return $profile;
            }
        }

        $preset = FrontendThemePresets::profile(FrontendThemePresets::defaultProfileKey());

        return [
            'name' => $preset['name'],
            'description' => $preset['description'],
            'primary_color' => $preset['primary_color'],
            'secondary_color' => $preset['secondary_color'],
            'accent_color' => $preset['accent_color'],
            'background_color' => $preset['background_color'],
            'surface_color' => $preset['surface_color'],
            'surface_strong_color' => $preset['surface_strong_color'],
            'ink_color' => $preset['ink_color'],
            'text_color' => $preset['text_color'],
            'muted_color' => $preset['muted_color'],
            'body_font' => $preset['body_font'],
            'display_font' => $preset['display_font'],
        ];
    }

    private function hasCarouselSlidesTable(): bool
    {
        return Schema::hasTable('carousel_slides');
    }

    private function hasContactPagesTable(): bool
    {
        return Schema::hasTable('contact_pages');
    }

    private function footerSettings(): FrontendFooterSetting|array
    {
        if (Schema::hasTable('frontend_footer_settings')) {
            $settings = FrontendFooterSetting::query()
                ->active()
                ->latest('id')
                ->first();

            if ($settings !== null) {
                return $settings;
            }
        }

        return [
            'brand_name' => 'CERAPE',
            'tagline' => 'Um frontend institucional pensado para comunicar com clareza, manter o conteudo organizado e facilitar o trabalho da equipe no Filament.',
            'address' => data_get($this->contactPage(), 'address') ?: 'Fazenda - R. 5 A - Parque Alvorada III, Luziânia - GO, 72859-899',
            'email' => data_get($this->contactPage(), 'email'),
            'phone' => data_get($this->contactPage(), 'phone'),
            'whatsapp' => data_get($this->contactPage(), 'whatsapp'),
            'map_embed_code' => data_get($this->contactPage(), 'map_embed_code'),
            'map_embed_url' => data_get($this->contactPage(), 'map_embed_url') ?: 'https://www.google.com/maps?q=' . urlencode('Fazenda - R. 5 A - Parque Alvorada III, Luziânia - GO, 72859-899') . '&output=embed',
            'quick_links' => [],
            'social_links' => data_get($this->contactPage(), 'social_links', []),
            'copyright_text' => 'CERAPE. Todos os direitos reservados.',
            'use_theme_colors' => true,
            'background_color' => null,
            'text_color' => null,
            'muted_color' => null,
            'border_color' => null,
            'is_active' => true,
        ];
    }

    private function resolveBlogAuthor(BlogPost $post): ?User
    {
        if ($post->relationLoaded('author') && $post->author instanceof User) {
            return $post->author;
        }

        if ($post->author instanceof User) {
            return $post->author;
        }

        if (filled($post->author_name)) {
            return User::query()
                ->with('roles')
                ->where('name', $post->author_name)
                ->first();
        }

        return null;
    }

    private function mapEmbedSrc(mixed $value, ?string $fallbackAddress = null): ?string
    {
        return MapEmbedResolver::src($value, $fallbackAddress);
    }

    /**
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    private function testimonials(): Collection
    {
        if (Schema::hasTable('frontend_testimonials')) {
            $testimonials = \App\Models\FrontendTestimonial::query()
                ->active()
                ->ordered()
                ->limit(5)
                ->get()
                ->map(fn (FrontendTestimonial $testimonial): array => [
                    'name' => $testimonial->name,
                    'role' => $testimonial->role,
                    'summary' => $testimonial->summary,
                    'image' => $testimonial->image,
                    'image_alt' => $testimonial->image_alt,
                ]);

            if ($testimonials->isNotEmpty()) {
                return $testimonials;
            }
        }

        $home = Home::query()->latest('id')->first();
        $items = collect($home?->testimonials ?? []);

        if ($items->isEmpty()) {
            return collect([
                [
                    'name' => 'Família acolhida',
                    'role' => 'Cuidado e acompanhamento',
                    'summary' => 'O atendimento ficou mais humano, claro e organizado depois que o site passou a comunicar melhor cada etapa.',
                    'image' => 'grayscale/assets/img/demo-image-01.jpg',
                    'image_alt' => 'Família recebendo orientações',
                ],
                [
                    'name' => 'Equipe técnica',
                    'role' => 'Fluxo de trabalho',
                    'summary' => 'Os conteúdos estão muito mais fáceis de atualizar, e isso deixou a rotina da equipe bem mais leve.',
                    'image' => 'grayscale/assets/img/demo-image-02.jpg',
                    'image_alt' => 'Equipe reunida em trabalho',
                ],
                [
                    'name' => 'Acompanhamento social',
                    'role' => 'Comunicação institucional',
                    'summary' => 'A identidade visual transmite confiança e acolhimento sem perder a formalidade necessária.',
                    'image' => 'grayscale/assets/img/bg-signup.jpg',
                    'image_alt' => 'Acolhimento institucional',
                ],
                [
                    'name' => 'Rede de apoio',
                    'role' => 'Experiência do visitante',
                    'summary' => 'A navegação ficou intuitiva e o layout tornou a leitura rápida, bonita e muito mais convidativa.',
                    'image' => 'grayscale/assets/img/demo-image-01.jpg',
                    'image_alt' => 'Rede de apoio e orientação',
                ],
                [
                    'name' => 'Gestão e cuidado',
                    'role' => 'Atualização contínua',
                    'summary' => 'Ter tudo concentrado no painel `/frontend` ajuda bastante na manutenção e no crescimento do projeto.',
                    'image' => 'grayscale/assets/img/demo-image-02.jpg',
                    'image_alt' => 'Gestão de conteúdo',
                ],
            ]);
        }

        return $items->take(5)->values();
    }
}
