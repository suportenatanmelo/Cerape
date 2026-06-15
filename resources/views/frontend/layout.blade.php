@extends('frontend.site')

@php
    use Illuminate\Support\Arr;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

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
        ->map(function ($slide) use ($resolveImage): array {
            $imagePath = data_get($slide, 'image') ?? data_get($slide, 'image_url');

            return [
                'eyebrow' => data_get($slide, 'eyebrow'),
                'title' => data_get($slide, 'title'),
                'description' => data_get($slide, 'description'),
                'image_url' => $resolveImage($imagePath, asset('grayscale/assets/img/bg-signup.jpg')),
                'image_alt' => data_get($slide, 'image_alt') ?: data_get($slide, 'alt') ?: data_get($slide, 'title') ?: 'Slide do carrossel',
                'cta_label' => data_get($slide, 'cta_label') ?: data_get($slide, 'link_label'),
                'cta_url' => data_get($slide, 'cta_url') ?: data_get($slide, 'link_url'),
            ];
        })
        ->filter(fn (array $item): bool => filled($item['image_url']))
        ->values();

    $latestPosts = collect($blogPosts ?? []);
@endphp

@section('title', 'CERAPE | Início')
@section('meta_description', 'Pagina institucional do CERAPE com sobre, blog, carrossel e contato, administrada pelo Filament.')

@section('content')
    <section class="relative">
        <div class="mx-auto max-w-7xl px-4 pb-20 pt-10 sm:px-6 lg:px-8 lg:pb-28 lg:pt-16">
            <div class="grid items-center gap-12 lg:grid-cols-[1.05fr_0.95fr]">
                <div class="space-y-8">
                    <span class="section-kicker">Frontend institucional gerenciado no /frontend</span>

                    <div class="space-y-5">
                        <h1 class="font-display text-5xl font-bold tracking-tight text-white sm:text-6xl lg:text-7xl">
                            {{ $homeTitle }}
                        </h1>
                        <div class="max-w-2xl text-lg leading-8 text-slate-300">
                            {!! $homeSubtitle !!}
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-4">
                        <a href="#about" class="inline-flex items-center justify-center rounded-full bg-amber-400 px-6 py-3 text-sm font-bold text-slate-950 shadow-lg shadow-amber-500/20 transition hover:bg-amber-300">
                            Conhecer o projeto
                        </a>
                        <a href="{{ route('blog') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-6 py-3 text-sm font-bold text-white transition hover:bg-white/10">
                            Ver blog
                        </a>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="soft-panel p-5">
                            <div class="text-sm font-semibold text-slate-400">Gestao</div>
                            <div class="mt-2 text-2xl font-display font-bold text-white">Filament</div>
                        </div>
                        <div class="soft-panel p-5">
                            <div class="text-sm font-semibold text-slate-400">Conteudo</div>
                            <div class="mt-2 text-2xl font-display font-bold text-white">Blog</div>
                        </div>
                        <div class="soft-panel p-5">
                            <div class="text-sm font-semibold text-slate-400">Atendimento</div>
                            <div class="mt-2 text-2xl font-display font-bold text-white">Contato</div>
                        </div>
                        <div class="soft-panel p-5">
                            <div class="text-sm font-semibold text-slate-400">Destaques</div>
                            <div class="mt-2 text-2xl font-display font-bold text-white">Carrossel</div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute -inset-6 rounded-[2.5rem] bg-gradient-to-br from-[#f2c94c]/20 via-transparent to-[#2f6b45]/14 blur-2xl"></div>
                    <div class="glass-card relative overflow-hidden p-4">
                        <img src="{{ $heroImageUrl }}" alt="{{ $heroImageAlt }}" class="aspect-[4/5] w-full rounded-[1.75rem] object-cover" />
                        <div class="absolute inset-x-8 bottom-8 rounded-[1.5rem] border border-[#d8c98f]/10 bg-[#071b12]/75 p-5 backdrop-blur">
                            <div class="text-xs font-bold uppercase tracking-[0.3em] text-amber-200">Atualizacao rapida</div>
                            <div class="mt-2 font-display text-2xl font-bold text-white">Visual profissional com manutencao simples</div>
                            <p class="mt-3 text-sm leading-6 text-slate-300">O site foi desenhado para crescer com o time, mantendo o conteudo organizado no painel e a experiencia do visitante leve.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[0.92fr_1.08fr] lg:items-center">
                <div class="relative">
                    <div class="absolute -inset-5 rounded-[2.5rem] bg-[#f2c94c]/10 blur-2xl"></div>
                    <img src="{{ $aboutImageUrl }}" alt="{{ $aboutImageAlt }}" class="relative aspect-[4/5] w-full rounded-[2rem] object-cover shadow-2xl shadow-black/30" />
                    <div class="glass-card absolute -bottom-8 left-5 max-w-sm p-5">
                        <div class="text-xs font-bold uppercase tracking-[0.28em] text-amber-200">Pontos fortes</div>
                        <div class="mt-3 grid gap-3 text-sm text-slate-300">
                            <div class="flex items-center gap-3"><span class="h-2 w-2 rounded-full bg-amber-400"></span> Conteudo editavel pelo Filament</div>
                            <div class="flex items-center gap-3"><span class="h-2 w-2 rounded-full bg-emerald-400"></span> Blog e carrossel integrados</div>
                            <div class="flex items-center gap-3"><span class="h-2 w-2 rounded-full bg-[#8a6b34]"></span> Formulario de contato pronto</div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <span class="section-kicker">Sobre</span>
                    <h2 class="font-display text-4xl font-bold tracking-tight text-white sm:text-5xl">
                        {{ $aboutTitle }}
                    </h2>
                    <div class="prose-cerape max-w-2xl">
                        {!! $aboutSubtitle !!}
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="soft-panel p-5">
                            <div class="text-sm font-semibold text-slate-400">Missao</div>
                            <div class="mt-3 text-base leading-7 text-slate-200">Comunicar com clareza e acolher com consistencia.</div>
                        </div>
                        <div class="soft-panel p-5">
                            <div class="text-sm font-semibold text-slate-400">Visao</div>
                            <div class="mt-3 text-base leading-7 text-slate-200">Manter um site vivo, confiavel e facil de atualizar.</div>
                        </div>
                        <div class="soft-panel p-5">
                            <div class="text-sm font-semibold text-slate-400">Valor</div>
                            <div class="mt-3 text-base leading-7 text-slate-200">Unir design bonito, acesso rapido e gestao simples.</div>
                        </div>
                    </div>

                    <div class="rounded-[2rem] border border-white/10 bg-gradient-to-br from-white/5 to-white/[0.03] p-6">
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <div class="text-sm font-semibold uppercase tracking-[0.28em] text-amber-200">Conteudo institucional</div>
                                <div class="mt-3 text-slate-300">Use essa area para apresentar a organizacao, a equipe, a proposta de trabalho e os principais servicos.</div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold uppercase tracking-[0.28em] text-emerald-200">Atualizacao sem atrito</div>
                                <div class="mt-3 text-slate-300">O painel `/frontend` concentra hero, sobre, blog e carrossel em um unico fluxo de manutencao.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="space-y-4">
                    <span class="section-kicker">Projetos e carrossel</span>
                    <h2 class="font-display text-4xl font-bold tracking-tight text-white sm:text-5xl">{{ $projectsTitle }}</h2>
                    <div class="prose-cerape max-w-3xl">
                        {!! $projectsSubtitle !!}
                    </div>
                </div>
                <a href="{{ route('blog') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10">
                    Ver todas as publicacoes
                </a>
            </div>

            @if ($carouselSlides->isNotEmpty())
                <div class="relative mt-10" data-carousel data-carousel-index="0">
                    <div class="overflow-hidden rounded-[2rem] border border-white/10 bg-[#081f15]/60 shadow-2xl shadow-black/25">
                        <div class="flex transition-transform duration-700 ease-out" data-carousel-track>
                            @foreach ($carouselSlides as $slide)
                                <article class="relative min-w-full">
                                    <img src="{{ $slide['image_url'] }}" alt="{{ $slide['alt'] ?? $slide['title'] ?? 'Slide do carrossel' }}" class="h-[26rem] w-full object-cover sm:h-[30rem]" />
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#071b12] via-[#071b12]/30 to-transparent"></div>
                                    <div class="absolute inset-x-0 bottom-0 p-6 sm:p-10">
                                        <div class="glass-card max-w-2xl p-6 sm:p-8">
                                            <span class="section-kicker">Slide {{ $loop->iteration }}</span>
                                            <h3 class="mt-4 font-display text-3xl font-bold text-white">
                                                {{ $slide['title'] ?? 'Destaque institucional' }}
                                            </h3>
                                            @if (filled($slide['description'] ?? null))
                                                <div class="prose-cerape mt-4 text-slate-300">{!! $slide['description'] !!}</div>
                                            @endif
                                            @if (filled($slide['cta_url'] ?? null) && filled($slide['cta_label'] ?? null))
                                                <a href="{{ $slide['cta_url'] }}" class="mt-6 inline-flex items-center justify-center rounded-full bg-[#f2c94c] px-5 py-3 text-sm font-bold text-[#071b12] transition hover:bg-[#f5d76c]">
                                                    {{ $slide['cta_label'] }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <button type="button" data-carousel-prev class="absolute left-4 top-1/2 inline-flex -translate-y-1/2 items-center justify-center rounded-full border border-white/10 bg-[#071b12]/75 p-3 text-white shadow-lg shadow-black/20 transition hover:bg-white/10">
                        <span class="sr-only">Anterior</span>
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                    <button type="button" data-carousel-next class="absolute right-4 top-1/2 inline-flex -translate-y-1/2 items-center justify-center rounded-full border border-white/10 bg-[#071b12]/75 p-3 text-white shadow-lg shadow-black/20 transition hover:bg-white/10">
                        <span class="sr-only">Proximo</span>
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                    <div class="mt-6 flex items-center justify-center gap-2" data-carousel-dots>
                        @foreach ($carouselSlides as $slide)
                            <button
                                type="button"
                                data-carousel-dot
                                class="h-2.5 w-2.5 rounded-full bg-white/30 transition duration-300"
                                aria-label="Ir para o slide {{ $loop->iteration }}"
                                aria-current="{{ $loop->first ? 'true' : 'false' }}"
                            ></button>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="mt-10 rounded-[2rem] border border-dashed border-white/15 bg-white/5 p-8 text-center text-slate-300">
                    O carrossel ainda nao foi configurado no painel.
                </div>
            @endif
        </div>
    </section>

    <section id="blog" class="py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="space-y-4">
                    <span class="section-kicker">Blog</span>
                    <h2 class="font-display text-4xl font-bold tracking-tight text-white sm:text-5xl">Ultimas publicacoes</h2>
                    <p class="max-w-2xl text-lg leading-8 text-slate-300">
                        Conteudo institucional, avisos e novidades apresentados de forma clara para familias, equipe e visitantes.
                    </p>
                </div>
                <a href="{{ route('blog') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10">
                    Abrir blog completo
                </a>
            </div>

            <div class="mt-10 grid gap-6 lg:grid-cols-3">
                @forelse ($latestPosts as $post)
                    @include('frontend.partials.post-card', ['post' => $post])
                @empty
                    <div class="lg:col-span-3 rounded-[2rem] border border-dashed border-white/15 bg-white/5 p-8 text-center text-slate-300">
                        Nenhuma publicacao disponivel no momento.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="contact" class="py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[1fr_0.85fr]">
                <div class="space-y-6">
                    <span class="section-kicker">Contato</span>
                    <h2 class="font-display text-4xl font-bold tracking-tight text-white sm:text-5xl">{{ $signupTitle }}</h2>
                    <div class="prose-cerape max-w-2xl">
                        {!! $signupSubtitle !!}
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="soft-panel p-5">
                            <div class="text-sm font-semibold text-slate-400">E-mail</div>
                            <div class="mt-3 text-base font-medium text-white">contato@cerape.local</div>
                        </div>
                        <div class="soft-panel p-5">
                            <div class="text-sm font-semibold text-slate-400">Telefone</div>
                            <div class="mt-3 text-base font-medium text-white">(00) 00000-0000</div>
                        </div>
                        <div class="soft-panel p-5">
                            <div class="text-sm font-semibold text-slate-400">Endereço</div>
                            <div class="mt-3 text-base font-medium text-white">CERAPE</div>
                        </div>
                        <div class="soft-panel p-5">
                            <div class="text-sm font-semibold text-slate-400">Painel</div>
                            <div class="mt-3 text-base font-medium text-white">/frontend</div>
                        </div>
                    </div>
                </div>

                @include('frontend.partials.contact-form')
            </div>
        </div>
    </section>
@endsection
