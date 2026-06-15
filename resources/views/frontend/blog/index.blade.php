@extends('frontend.site')

@php
    $posts = $blogPosts;
@endphp

@section('title', 'CERAPE | Blog')
@section('meta_description', 'Blog institucional do CERAPE com publicacoes, noticias e comunicados.')

@section('content')
    <section class="relative">
        <div class="mx-auto max-w-7xl px-4 pb-16 pt-10 sm:px-6 lg:px-8 lg:pb-20 lg:pt-16">
            <div class="glass-card p-8 lg:p-10">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="space-y-5">
                        <span class="section-kicker">Blog institucional</span>
                        <h1 class="font-display text-4xl font-bold tracking-tight text-white sm:text-5xl">Publicacoes, avisos e novidades</h1>
                        <p class="max-w-3xl text-lg leading-8 text-slate-300">Acompanhe os conteudos publicados pela equipe, organizados para leitura rapida e exibidos em cards elegantes.</p>
                    </div>
                    <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-full bg-amber-400 px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-amber-300">
                        Falar com a equipe
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if ($posts->count() > 0)
                <div class="grid gap-6 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        @include('frontend.partials.post-card', ['post' => $post])
                    @endforeach
                </div>

                @if (method_exists($posts, 'links'))
                    <div class="mt-12">
                        {{ $posts->links() }}
                    </div>
                @endif
            @else
                <div class="rounded-[2rem] border border-dashed border-white/15 bg-white/5 p-8 text-center text-slate-300">
                    Nenhuma publicacao foi cadastrada ainda.
                </div>
            @endif
        </div>
    </section>
@endsection
