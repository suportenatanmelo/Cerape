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

    $aboutTitle = filled($home?->about_title) ? $home->about_title : 'Sobre o CERAPE';
    $aboutSubtitle = filled($home?->about_subtitle)
        ? $home->about_subtitle
        : 'O site publico foi desenhado para apresentar a instituicao, fortalecer a relacao com a familia e centralizar noticias, conteudos e contato em um unico lugar.';
    $aboutImageUrl = $resolveImage($home?->about_image, asset('grayscale/assets/img/demo-image-01.jpg'));
    $aboutImageAlt = filled($home?->about_image_alt) ? $home->about_image_alt : 'Institucional CERAPE';
    $projectsImageUrl = $resolveImage($home?->projects_image, asset('grayscale/assets/img/demo-image-02.jpg'));
    $projectsImageAlt = filled($home?->projects_image_alt) ? $home->projects_image_alt : 'Projetos do CERAPE';
    $blogPosts = collect($blogPosts ?? []);
@endphp

@section('title', 'CERAPE | Sobre')
@section('meta_description', 'Pagina sobre o CERAPE, com proposta, valores e resumo do site institucional.')

@section('content')
    <section class="relative">
        <div class="mx-auto max-w-7xl px-4 pb-16 pt-10 sm:px-6 lg:px-8 lg:pb-24 lg:pt-16">
            <div class="glass-card overflow-hidden p-8 lg:p-10">
                <div class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
                    <div class="space-y-6">
                        <span class="section-kicker">Sobre a instituição</span>
                        <h1 class="font-display text-4xl font-bold tracking-tight text-white sm:text-5xl">Quem somos e como este frontend foi pensado</h1>
                        <div class="prose-cerape max-w-2xl">{!! $aboutSubtitle !!}</div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('blog') }}" class="inline-flex items-center justify-center rounded-full bg-amber-400 px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-amber-300">Ir para o blog</a>
                            <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10">Falar com a equipe</a>
                        </div>
                    </div>
                    <div>
                        <img src="{{ $aboutImageUrl }}" alt="{{ $aboutImageAlt }}" class="aspect-[4/5] w-full rounded-[2rem] object-cover shadow-2xl shadow-black/30" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-3">
                <div class="soft-panel p-6">
                    <div class="text-sm font-semibold uppercase tracking-[0.28em] text-amber-200">Missao</div>
                    <div class="mt-3 text-slate-300">Comunicar com clareza, organizar informacoes e facilitar o acesso das familias.</div>
                </div>
                <div class="soft-panel p-6">
                    <div class="text-sm font-semibold uppercase tracking-[0.28em] text-cyan-200">Visao</div>
                    <div class="mt-3 text-slate-300">Manter uma presenca publica coerente, profissional e facil de manter no painel.</div>
                </div>
                <div class="soft-panel p-6">
                    <div class="text-sm font-semibold uppercase tracking-[0.28em] text-emerald-200">Valores</div>
                    <div class="mt-3 text-slate-300">Acolhimento, transparencia, acessibilidade e simplicidade de operacao.</div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
                <div>
                    <img src="{{ $projectsImageUrl }}" alt="{{ $projectsImageAlt }}" class="aspect-[4/5] w-full rounded-[2rem] object-cover shadow-2xl shadow-black/30" />
                </div>
                <div class="space-y-6">
                    <span class="section-kicker">Como o site funciona</span>
                    <h2 class="font-display text-4xl font-bold tracking-tight text-white sm:text-5xl">Um frontend bonito, mas tambem simples de operar</h2>
                    <div class="prose-cerape max-w-2xl">
                        <p>O painel `/frontend` concentra a edicao da home, do carrossel e do blog. Isso reduz retrabalho e evita que o conteudo fique espalhado por varias ferramentas.</p>
                        <p>As publicacoes sao exibidas com cards elegantes, enquanto o formulario de contato recebe mensagens de forma direta e organizada.</p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="soft-panel p-5">
                            <div class="font-display text-2xl font-bold text-white">Home</div>
                            <p class="mt-2 text-sm leading-7 text-slate-300">Hero, sobre, carrossel e CTA editaveis no Filament.</p>
                        </div>
                        <div class="soft-panel p-5">
                            <div class="font-display text-2xl font-bold text-white">Blog</div>
                            <p class="mt-2 text-sm leading-7 text-slate-300">Posts com capa, resumo, status e conteudo rico.</p>
                        </div>
                        <div class="soft-panel p-5">
                            <div class="font-display text-2xl font-bold text-white">Contato</div>
                            <p class="mt-2 text-sm leading-7 text-slate-300">Formulario simples e direto para retorno rapido.</p>
                        </div>
                        <div class="soft-panel p-5">
                            <div class="font-display text-2xl font-bold text-white">Carrossel</div>
                            <p class="mt-2 text-sm leading-7 text-slate-300">Slides com imagem, texto e botao opcional.</p>
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
                    <span class="section-kicker">Ultimas publicacoes</span>
                    <h2 class="font-display text-4xl font-bold tracking-tight text-white sm:text-5xl">Conteudo recente</h2>
                </div>
                <a href="{{ route('blog') }}" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10">Abrir blog completo</a>
            </div>

            <div class="mt-10 grid gap-6 lg:grid-cols-3">
                @forelse ($blogPosts as $post)
                    @include('frontend.partials.post-card', ['post' => $post])
                @empty
                    <div class="lg:col-span-3 rounded-[2rem] border border-dashed border-white/15 bg-white/5 p-8 text-center text-slate-300">
                        Nenhuma publicacao disponivel no momento.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
