@extends('frontend.site')

@php
    use Illuminate\Support\Arr;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
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

    $coverImage = $resolveImage($post->cover_image, asset('grayscale/assets/img/bg-masthead.jpg'));
    $relatedPosts = collect($relatedPosts ?? []);
    $authorProfile = $authorProfile ?? null;

    $renderRich = function ($value): string {
        if (blank($value)) {
            return '';
        }

        return RichContentRenderer::make($value)
            ->textColors(FrontendTextColors::palette())
            ->toHtml();
    };
@endphp

@section('title', $post->title . ' | CERAPE')
@section('meta_description', Str::limit(strip_tags((string) $post->excerpt), 155))

@section('content')
    <article>
        <section class="relative">
            <div class="mx-auto max-w-7xl px-4 pb-16 pt-10 sm:px-6 lg:px-8 lg:pb-20 lg:pt-16">
                <div class="glass-card overflow-hidden">
                    <div class="overflow-hidden bg-slate-100">
                        <img src="{{ $coverImage }}" alt="{{ $post->cover_image_alt ?: $post->title }}" class="h-[16rem] w-full object-cover sm:h-[20rem] lg:h-[24rem]" />
                    </div>
                    <div class="p-8 lg:p-10">
                        <div class="flex flex-wrap items-center gap-3">
                            @if (filled($post->category))
                                <span class="section-kicker">{{ $post->category }}</span>
                            @endif
                            @if ($post->is_featured)
                                <span class="rounded-full bg-amber-400 px-3 py-1 text-xs font-bold uppercase tracking-[0.24em] text-slate-950">Destaque</span>
                            @endif
                            <span class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-[0.24em] text-slate-600">
                                {{ optional($post->published_at)->format('d/m/Y') ?: 'Em breve' }}
                            </span>
                        </div>
                        <h1 class="mt-6 font-display text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">{{ $post->title }}</h1>
                        <div class="prose-cerape mt-5 max-w-3xl text-lg leading-8 text-slate-600">{!! $renderRich($post->excerpt) !!}</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="pb-20">
            <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1.05fr_0.45fr] lg:px-8">
                <div class="glass-card p-8 lg:p-10">
                    <div class="prose-cerape max-w-none text-slate-600">
                        {!! $renderRich($post->content) !!}
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="soft-panel p-6">
                        <div class="text-sm font-semibold uppercase tracking-[0.28em] text-[var(--site-primary)]">Autor</div>
                        <div class="mt-5 flex flex-col items-center text-center">
                            <div class="flex h-24 w-24 items-center justify-center overflow-hidden rounded-full border-4 border-white bg-slate-100 shadow-lg shadow-slate-200/60">
                                @if (filled(data_get($authorProfile, 'avatar')))
                                    <img src="{{ data_get($authorProfile, 'avatar_url') ?: $authorProfile->getFilamentAvatarUrl() }}" alt="{{ data_get($authorProfile, 'name') }}" class="h-full w-full object-cover" />
                                @else
                                    <span class="font-display text-3xl font-bold text-slate-500">{{ Str::of(data_get($authorProfile, 'name', $post->author_name ?: 'CERAPE'))->substr(0, 1)->upper() }}</span>
                                @endif
                            </div>
                            <div class="mt-4 text-xl font-display font-bold text-slate-900">{{ data_get($authorProfile, 'name') ?: $post->author_name ?: 'CERAPE' }}</div>
                            <div class="mt-2 text-sm leading-7 text-slate-600">
                                {{ data_get($authorProfile, 'roles.0.name') ? ucfirst(str_replace('_', ' ', data_get($authorProfile, 'roles.0.name'))) : 'Autor institucional' }}
                            </div>
                        </div>
                        <div class="mt-5 space-y-3 rounded-[1.4rem] border border-slate-200 bg-white p-4 text-sm leading-6 text-slate-600">
                            <div><span class="font-semibold text-slate-900">E-mail:</span> {{ data_get($authorProfile, 'email') ?: 'Nao informado' }}</div>
                            <div><span class="font-semibold text-slate-900">Telefone:</span> {{ data_get($authorProfile, 'phone_whatsapp') ?: 'Nao informado' }}</div>
                            <div><span class="font-semibold text-slate-900">UF:</span> {{ data_get($authorProfile, 'uf') ?: 'Nao informado' }}</div>
                            <div><span class="font-semibold text-slate-900">Endereco:</span> {{ data_get($authorProfile, 'endereco') ?: data_get($authorProfile, 'address') ?: 'Nao informado' }}</div>
                        </div>
                    </div>

                    <div class="soft-panel p-6">
                        <div class="text-sm font-semibold uppercase tracking-[0.28em] text-[var(--site-primary)]">Contato</div>
                        <div class="mt-3 text-slate-600">Precisa falar com a equipe? O formulario de contato esta sempre disponivel para mensagens e retornos.</div>
                        <a href="{{ route('contact') }}" class="mt-5 inline-flex items-center justify-center rounded-full bg-amber-400 px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-amber-300">
                            Abrir contato
                        </a>
                    </div>

                    @if ($relatedPosts->isNotEmpty())
                        <div class="soft-panel p-6">
                            <div class="text-sm font-semibold uppercase tracking-[0.28em] text-[var(--site-primary)]">Leituras relacionadas</div>
                            <div class="mt-4 space-y-4">
                                @foreach ($relatedPosts as $relatedPost)
                                    <a href="{{ route('blog.show', $relatedPost) }}" class="block rounded-2xl border border-slate-200 bg-white p-4 transition hover:bg-slate-50">
                                        <div class="text-xs font-bold uppercase tracking-[0.28em] text-slate-500">{{ $relatedPost->category ?: 'Blog' }}</div>
                                        <div class="mt-2 font-display text-lg font-bold text-slate-900">{{ $relatedPost->title }}</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        </section>
    </article>
@endsection
