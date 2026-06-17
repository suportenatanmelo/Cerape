@extends('frontend.site')

@php
    use Illuminate\Support\Arr;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use App\Support\MapEmbedResolver;
    use App\Support\FrontendTextColors;
    use Filament\Forms\Components\RichEditor\RichContentRenderer;

    $resolveImage = function ($path, ?string $fallback = null): ?string {
        if (blank($path)) {
            return $fallback;
        }

        if (is_array($path)) {
            $path = Arr::first($path);
        }

        if (Str::startsWith((string) $path, ['http://', 'https://', '//'])) {
            return (string) $path;
        }

        if (Storage::disk('public')->exists((string) $path)) {
            return Storage::disk('public')->url((string) $path);
        }

        return asset((string) $path);
    };

    $resolveColor = function ($value, string $fallback): string {
        $value = trim((string) $value);

        if ($value === '') {
            return $fallback;
        }

        return $value;
    };

    $renderRich = function ($value): string {
        if (blank($value)) {
            return '';
        }

        return RichContentRenderer::make($value)
            ->textColors(FrontendTextColors::palette())
            ->toHtml();
    };

    $homeTitle = filled($home?->title) ? $home->title : 'CERAPE';
    $homeSubtitle = filled($home?->subtitle)
        ? $home->subtitle
        : 'Acolhimento, acompanhamento e comunicacao institucional em um site elegante, claro e facil de atualizar pelo painel.';

    $heroImageUrl = $resolveImage($home?->hero_image, asset('grayscale/assets/img/bg-masthead.jpg'));
    $heroImageAlt = filled($home?->hero_image_alt) ? $home->hero_image_alt : 'Equipe CERAPE em destaque';

    $aboutTitle = filled($home?->about_title) ? $home->about_title : 'Sobre o CERAPE';
    $aboutSubtitle = filled($home?->about_subtitle)
        ? $home->about_subtitle
        : 'O site publico foi desenhado para apresentar a instituicao, fortalecer a relacao com a familia e centralizar noticias, conteudos e contato em um unico lugar.';
    $aboutImageUrl = $resolveImage($home?->about_image, asset('grayscale/assets/img/demo-image-01.jpg'));
    $aboutImageAlt = filled($home?->about_image_alt) ? $home->about_image_alt : 'Institucional CERAPE';

    $projectsTitle = filled($home?->projects_title) ? $home->projects_title : 'Servicos e iniciativas';
    $projectsSubtitle = filled($home?->projects_subtitle)
        ? $home->projects_subtitle
        : 'Use esta area para destacar projetos, programas e informacoes que merecem atencao especial na pagina inicial.';
    $projectsImageUrl = $resolveImage($home?->projects_image, asset('grayscale/assets/img/demo-image-02.jpg'));
    $projectsImageAlt = filled($home?->projects_image_alt) ? $home->projects_image_alt : 'Projetos do CERAPE';

    $signupTitle = filled($home?->signup_title) ? $home->signup_title : 'Fale com a equipe';
    $signupSubtitle = filled($home?->signup_subtitle)
        ? $home->signup_subtitle
        : 'Envie uma mensagem pelo formulario e retorne com facilidade. O contato tambem pode ser atualizado pelo painel, se necessario.';

    $carouselSlides = collect($carouselSlides ?? [])
        ->map(function ($slide) use ($resolveImage, $resolveColor): array {
            $imagePath = data_get($slide, 'image') ?? data_get($slide, 'image_url');

            return [
                'eyebrow' => data_get($slide, 'eyebrow'),
                'eyebrow_color' => $resolveColor(data_get($slide, 'eyebrow_color'), '#f2c94c'),
                'title' => data_get($slide, 'title'),
                'title_color' => $resolveColor(data_get($slide, 'title_color'), '#ffffff'),
                'description' => data_get($slide, 'description'),
                'description_color' => $resolveColor(data_get($slide, 'description_color'), '#e5e7eb'),
                'image_url' => $resolveImage($imagePath, asset('grayscale/assets/img/bg-signup.jpg')),
                'image_alt' => data_get($slide, 'image_alt') ?: data_get($slide, 'alt') ?: data_get($slide, 'title') ?: 'Slide do carrossel',
                'cta_label' => data_get($slide, 'cta_label') ?: data_get($slide, 'link_label'),
                'cta_text_color' => $resolveColor(data_get($slide, 'cta_text_color'), '#140f05'),
                'cta_url' => data_get($slide, 'cta_url') ?: data_get($slide, 'link_url'),
            ];
        })
        ->filter(fn (array $item): bool => filled($item['image_url']))
        ->values();

    $latestPosts = collect($blogPosts ?? []);
    $testimonials = collect($testimonials ?? [])
        ->map(function ($item) use ($resolveImage): array {
            return [
                'name' => data_get($item, 'name') ?: 'Depoimento',
                'role' => data_get($item, 'role'),
                'summary' => data_get($item, 'summary'),
                'image_url' => $resolveImage(data_get($item, 'image'), asset('grayscale/assets/img/demo-image-01.jpg')),
                'image_alt' => data_get($item, 'image_alt') ?: data_get($item, 'name') ?: 'Card de depoimento',
            ];
        })
        ->filter(fn (array $item): bool => filled($item['summary']) || filled($item['image_url']))
        ->take(5)
        ->values();
@endphp

@section('title', 'CERAPE | Início')
@section('meta_description', 'Pagina institucional do CERAPE com sobre, blog, carrossel e contato, administrada pelo Filament.')

@section('content')
    @php
        $heroSlides = $carouselSlides->isNotEmpty()
            ? $carouselSlides->values()
            : collect([
                [
                    'title' => $homeTitle,
                    'description' => $homeSubtitle,
                    'image_url' => $heroImageUrl,
                    'image_alt' => $heroImageAlt,
                    'cta_label' => 'Conheça o projeto',
                    'cta_url' => '#contact',
                    'secondary_cta_label' => 'Saiba mais',
                    'secondary_cta_url' => '#about',
                ],
            ]);

        $defaultFeatureCards = [
            [
                'title' => 'Acolhimento Humanizado',
                'description' => 'Atendimento com empatia, respeito e dedicação.',
                'icon' => 'heroicon-o-heart',
            ],
            [
                'title' => 'Equipe Especializada',
                'description' => 'Profissionais capacitados e experientes.',
                'icon' => 'heroicon-o-users',
            ],
            [
                'title' => 'Tratamentos Personalizados',
                'description' => 'Planos terapêuticos individualizados.',
                'icon' => 'heroicon-o-sparkles',
            ],
            [
                'title' => 'Reintegração Social',
                'description' => 'Apoio para retornar à vida em sociedade.',
                'icon' => 'heroicon-o-arrow-top-right-on-square',
            ],
        ];

        $featureCards = collect($home?->feature_cards ?? [])
            ->map(function ($card): array {
                return [
                    'title' => data_get($card, 'title'),
                    'description' => data_get($card, 'description'),
                    'icon' => data_get($card, 'icon'),
                ];
            })
            ->filter(fn (array $card): bool => filled($card['title']) || filled($card['description']) || filled($card['icon']))
            ->values();

        if ($featureCards->isEmpty()) {
            $featureCards = collect($defaultFeatureCards);
        }

        $defaultTreatmentCards = [
            [
                'title' => 'Dependência Química',
                'description' => 'Tratamento completo com suporte, escuta e construção de rotina.',
                'image_url' => $resolveImage('grayscale/assets/img/demo-image-01.jpg', asset('grayscale/assets/img/demo-image-01.jpg')),
                'image_alt' => 'Tratamento para dependência quimica',
            ],
            [
                'title' => 'Alcoolismo',
                'description' => 'Apoio especializado para superar e recomeçar.',
                'image_url' => $resolveImage('grayscale/assets/img/demo-image-02.jpg', asset('grayscale/assets/img/demo-image-02.jpg')),
                'image_alt' => 'Tratamento para alcoolismo',
            ],
            [
                'title' => 'Terapia em Grupo',
                'description' => 'Fortalecimento emocional com atividades terapêuticas.',
                'image_url' => $resolveImage('grayscale/assets/img/bg-signup.jpg', asset('grayscale/assets/img/bg-signup.jpg')),
                'image_alt' => 'Terapia em grupo',
            ],
            [
                'title' => 'Reintegração Social',
                'description' => 'Preparação gradual para retomar vínculos e autonomia.',
                'image_url' => $resolveImage('grayscale/assets/img/bg-masthead.jpg', asset('grayscale/assets/img/bg-masthead.jpg')),
                'image_alt' => 'Reintegração social',
            ],
        ];

        $treatmentCards = collect($home?->treatment_cards ?? [])
            ->map(function ($card) use ($resolveImage): array {
                return [
                    'title' => data_get($card, 'title'),
                    'description' => data_get($card, 'description'),
                    'image_url' => $resolveImage(data_get($card, 'image'), asset('grayscale/assets/img/demo-image-01.jpg')),
                    'image_alt' => data_get($card, 'image_alt') ?: data_get($card, 'title') ?: 'Tratamento',
                ];
            })
            ->filter(fn (array $card): bool => filled($card['title']) || filled($card['description']) || filled($card['image_url']))
            ->values();

        if ($treatmentCards->isEmpty()) {
            $treatmentCards = collect($defaultTreatmentCards);
        }

        $homeAddress = data_get($footerSettings ?? null, 'address')
            ?: data_get($contactPage ?? null, 'address')
            ?: 'Fazenda - R. 5 A - Parque Alvorada III, Luziânia - GO, 72859-899';
        $homeMapSrc = MapEmbedResolver::src(
            data_get($footerSettings ?? null, 'map_embed_code')
                ?: data_get($footerSettings ?? null, 'map_embed_url')
                ?: data_get($contactPage ?? null, 'map_embed_code')
                ?: data_get($contactPage ?? null, 'map_embed_url'),
            $homeAddress
        );

        $newsCards = $latestPosts->take(3);
    @endphp

    <section class="pt-5 pb-14 md:pb-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-4 text-center">
                <span class="inline-flex items-center rounded-full bg-[#224b85] px-4 py-2 text-xs font-bold uppercase tracking-[0.22em] text-white shadow-sm">
                    FRONTEND (SITE PÚBLICO)
                </span>
            </div>

            <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-[0_24px_60px_rgba(15,23,42,0.08)]">
                <div class="relative" data-carousel data-carousel-index="0">
                    <div class="overflow-hidden">
                        <div class="flex transition-transform duration-700 ease-out" data-carousel-track>
                            @foreach ($heroSlides as $slide)
                                <article class="hero-slide relative min-w-full">
                                    <img src="{{ $slide['image_url'] }}" alt="{{ $slide['image_alt'] ?? $slide['title'] ?? 'Slide do carrossel' }}" class="h-[22rem] w-full object-cover sm:h-[26rem] lg:h-[30rem]" />
                                    <div class="absolute inset-0 bg-gradient-to-r from-[#0b274c]/92 via-[#0b274c]/58 to-transparent"></div>
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="mx-auto flex w-full max-w-7xl items-center px-6 sm:px-10 lg:px-12">
                                            <div class="hero-banner max-w-lg">
                                                <span class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.28em] hero-muted">
                                                    CERAPE
                                                </span>
                                                <h1 class="mt-4 font-display text-3xl font-bold leading-tight sm:text-4xl lg:text-5xl">
                                                    {{ $slide['title'] ?? $homeTitle }}
                                                </h1>
                                                @if (filled($slide['description'] ?? null))
                                                    <p class="hero-soft mt-3 max-w-md text-sm leading-6 sm:text-base">
                                                        {!! $renderRich($slide['description']) !!}
                                                    </p>
                                                @endif

                                                <div class="mt-5 flex flex-wrap gap-3">
                                                    @if ($loop->first)
                                                        <a href="{{ $slide['cta_url'] ?? '#contact' }}" class="inline-flex items-center justify-center rounded-full bg-emerald-500 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/25 transition hover:bg-emerald-400">
                                                            {{ $slide['cta_label'] ?? 'Conheça o projeto' }}
                                                        </a>
                                                        <a href="{{ $slide['secondary_cta_url'] ?? '#about' }}" class="inline-flex items-center justify-center rounded-full border border-white/25 bg-white/10 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-white/15">
                                                            {{ $slide['secondary_cta_label'] ?? 'Saiba mais' }}
                                                        </a>
                                                    @elseif (filled($slide['cta_url'] ?? null) && filled($slide['cta_label'] ?? null))
                                                        <a href="{{ $slide['cta_url'] }}" class="inline-flex items-center justify-center rounded-full bg-emerald-500 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/25 transition hover:bg-emerald-400">
                                                            {{ $slide['cta_label'] }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <button type="button" data-carousel-prev class="absolute left-4 top-1/2 inline-flex -translate-y-1/2 items-center justify-center rounded-full border border-white/20 bg-black/20 p-2.5 text-white shadow-lg backdrop-blur transition hover:bg-black/30">
                        <span class="sr-only">Anterior</span>
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                    <button type="button" data-carousel-next class="absolute right-4 top-1/2 inline-flex -translate-y-1/2 items-center justify-center rounded-full border border-white/20 bg-black/20 p-2.5 text-white shadow-lg backdrop-blur transition hover:bg-black/30">
                        <span class="sr-only">Proximo</span>
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                    <div class="absolute inset-x-0 bottom-5 flex items-center justify-center gap-2" data-carousel-dots>
                        @foreach ($heroSlides as $slide)
                            <button
                                type="button"
                                data-carousel-dot
                                class="h-2.5 w-2.5 rounded-full bg-white/45 transition duration-300"
                                aria-label="Ir para o slide {{ $loop->iteration }}"
                                aria-current="{{ $loop->first ? 'true' : 'false' }}"
                            ></button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-6 pb-16 md:pt-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($featureCards as $card)
                    <article class="rounded-[1.5rem] border border-slate-200 bg-white p-6 text-center shadow-[0_12px_30px_rgba(15,23,42,0.05)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_18px_36px_rgba(15,23,42,0.08)]">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[color-mix(in_srgb,var(--site-primary)_12%,white)] text-[var(--site-primary)]">
                            @if (filled($card['icon']) && Str::startsWith((string) $card['icon'], 'heroicon-'))
                                <x-filament::icon :icon="$card['icon']" class="h-6 w-6" />
                            @else
                                <span class="text-lg font-bold">•</span>
                            @endif
                        </div>
                        <h3 class="mt-4 font-display text-lg font-bold text-slate-900">{{ $card['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $card['description'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="treatments" class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <span class="section-kicker">Tratamentos</span>
                <h2 class="mt-4 font-display text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">Nossos tratamentos</h2>
                <p class="mx-auto mt-3 max-w-2xl text-base leading-7 text-slate-600">
                    Um espaço para apresentar os principais serviços com clareza, acolhimento e foco na recuperação.
                </p>
            </div>

            <div class="mt-10 grid gap-6 lg:grid-cols-[1.4fr_0.9fr]">
                <div>
                    <div class="grid gap-5 [grid-template-columns:repeat(auto-fit,minmax(16rem,1fr))]">
                    @foreach ($treatmentCards as $card)
                        <article class="overflow-hidden rounded-[1.6rem] border border-slate-200 bg-white shadow-[0_16px_36px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(15,23,42,0.08)]">
                            <img src="{{ $card['image_url'] }}" alt="{{ $card['image_alt'] ?? $card['title'] }}" class="h-40 w-full object-cover" />
                            <div class="p-5">
                                <h3 class="font-display text-xl font-bold text-slate-900">{{ $card['title'] }}</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-600">{{ $card['description'] }}</p>
                                <a href="{{ route('contact') }}" class="mt-4 inline-flex items-center justify-center text-xs font-bold uppercase tracking-[0.22em] text-[#1f5fa8] transition hover:text-[#153f72]">
                                    Saiba mais +
                                </a>
                            </div>
                        </article>
                    @endforeach
                    </div>
                </div>

                <aside class="rounded-[1.6rem] border border-slate-200 bg-white p-6 shadow-[0_16px_36px_rgba(15,23,42,0.06)]">
                    <div class="flex items-center justify-between">
                        <h3 class="font-display text-2xl font-bold text-slate-900">Últimas notícias</h3>
                        <a href="{{ route('blog') }}" class="text-xs font-bold uppercase tracking-[0.22em] text-[#1f5fa8]">Ver blog</a>
                    </div>

                    <div class="mt-5 space-y-4">
                        @forelse ($newsCards as $post)
                            <a href="{{ route('blog.show', $post->slug) }}" class="flex gap-3 rounded-[1.2rem] border border-slate-200 p-3 transition hover:border-[#1f5fa8]/25 hover:bg-slate-50">
                                <img src="{{ $post->cover_image_url ?? $heroImageUrl }}" alt="{{ $post->cover_image_alt ?: $post->title }}" class="h-16 w-16 flex-none rounded-xl object-cover" />
                                <div class="min-w-0">
                                    <h4 class="truncate text-sm font-bold text-slate-900">{{ $post->title }}</h4>
                                    <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-600">{{ $post->excerpt }}</p>
                                </div>
                            </a>
                        @empty
                            <div class="rounded-[1.2rem] border border-dashed border-slate-200 p-4 text-sm text-slate-600">
                                Nenhuma publicação disponível.
                            </div>
                        @endforelse
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <section class="py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-[2.1rem] border border-slate-200 bg-white shadow-[0_20px_48px_rgba(15,23,42,0.08)]">
                <div class="grid gap-0 lg:grid-cols-[0.95fr_1.05fr]">
                    <div class="relative overflow-hidden p-8 lg:p-10">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(59,130,246,0.10),transparent_34%),radial-gradient(circle_at_bottom_right,rgba(16,185,129,0.08),transparent_32%)]"></div>
                        <div class="relative">
                            <span class="section-kicker">Localização</span>
                            <h2 class="mt-4 font-display text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">Onde o CERAPE está localizado</h2>
                            <p class="mt-4 max-w-xl text-base leading-8 text-slate-600">
                                {{ $homeAddress }}
                            </p>

                            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                                <div class="rounded-[1.2rem] border border-slate-200 bg-white px-4 py-4">
                                    <div class="text-[10px] font-bold uppercase tracking-[0.24em] text-slate-500">Visita</div>
                                    <div class="mt-2 text-sm font-semibold text-slate-900">Localização institucional</div>
                                </div>
                                <div class="rounded-[1.2rem] border border-slate-200 bg-white px-4 py-4">
                                    <div class="text-[10px] font-bold uppercase tracking-[0.24em] text-slate-500">Mapa</div>
                                    <div class="mt-2 text-sm font-semibold text-slate-900">Google Maps integrado</div>
                                </div>
                            </div>

                            <div class="mt-6 flex flex-wrap gap-3">
                                <a href="{{ MapEmbedResolver::searchUrl($homeAddress) }}" target="_blank" rel="noreferrer" class="inline-flex items-center justify-center rounded-full bg-[var(--site-primary)] px-5 py-3 text-sm font-bold text-white transition hover:opacity-90">
                                    Abrir no Google Maps
                                </a>
                                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-900 transition hover:bg-slate-50">
                                    Ver contato
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="min-h-[24rem] bg-slate-100 p-4">
                        @if (filled($homeMapSrc))
                            <div class="h-full overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-[0_16px_32px_rgba(15,23,42,0.08)]">
                                <iframe
                                    src="{{ $homeMapSrc }}"
                                    class="h-full min-h-[24rem] w-full"
                                    style="border: 0;"
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                    title="Localização do CERAPE"
                                ></iframe>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="testimonials" class="py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] border border-slate-200 bg-slate-50 p-6 sm:p-8">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div class="space-y-3">
                        <span class="section-kicker">Depoimentos</span>
                        <h2 class="font-display text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">O que as famílias dizem</h2>
                    </div>
                </div>

                <div class="mt-8 overflow-x-auto pb-2">
                    <div class="flex min-w-max gap-3">
                        @forelse ($testimonials as $testimonial)
                            <article class="flex w-[18rem] flex-none items-center gap-3 rounded-[1.15rem] border border-slate-200 bg-white p-3 shadow-sm">
                                <div class="h-14 w-14 flex-none overflow-hidden rounded-[0.95rem]">
                                    <img src="{{ $testimonial['image_url'] }}" alt="{{ $testimonial['image_alt'] }}" class="h-full w-full object-cover" />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="truncate text-[10px] font-bold uppercase tracking-[0.22em]" style="color: color-mix(in srgb, var(--site-primary) 84%, #0f172a);">
                                        {{ $testimonial['role'] }}
                                    </div>
                                    <h3 class="truncate font-display text-sm font-bold text-slate-900">{{ $testimonial['name'] }}</h3>
                                    <p class="testimonial-summary text-[0.72rem] leading-4 text-slate-600">
                                        {{ $testimonial['summary'] }}
                                    </p>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-[1.2rem] border border-dashed border-slate-300 bg-white p-8 text-center text-slate-600">
                                Nenhum depoimento foi configurado ainda.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
