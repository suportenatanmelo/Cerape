@extends('frontend.layout')

@section('meta_title', ($settings?->blog_title ? $settings->blog_title . ' | ' : '') . ($settings?->brand_name ?? 'CERAPE'))
@section('meta_description', $settings?->blog_description ?? 'Artigos para famílias e pacientes sobre recuperação, saúde mental e reconstrução de vínculos.')
@section('meta_type', 'website')
@section('meta_canonical', route('blog.index'))

@section('content')
    <section class="section" style="padding-top: 8rem;">
        <div class="section-head reveal">
            <span class="eyebrow">{{ $settings?->blog_eyebrow ?? 'Conteúdo' }}</span>
            <h1>{{ $settings?->blog_title ?? 'Blog' }}</h1>
            <p>{{ $settings?->blog_description ?? 'Artigos para famílias e pacientes sobre recuperação, saúde mental e reconstrução de vínculos.' }}</p>
        </div>

        <div class="blog-grid">
            @forelse ($posts as $post)
                <article class="post-card reveal">
                    @if ($post->imageUrl())
                        <div class="post-img">
                            <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" loading="lazy">
                        </div>
                    @endif
                    <div class="post-body">
                        <div class="post-meta">
                            <span class="post-tag">{{ $post->author_name ?: 'Equipe CERAPE' }}</span>
                            <span>{{ optional($post->published_at)->format('d/m/Y') }}</span>
                        </div>
                        <h3>{{ $post->title }}</h3>
                        <p>{{ $post->excerpt }}</p>
                        <p class="post-tags">Tags: {{ is_array($post->tags) ? implode(', ', $post->tags) : ($post->tags ?? '-') }}</p>
                        @if ($post->slug)
                            <a class="btn btn-line" href="{{ route('blog.show', ['slug' => $post->slug]) }}">Leia mais</a>
                        @endif
                    </div>
                </article>
            @empty
                <article class="post-card">
                    <div class="post-body">
                        <p>{{ $settings?->blog_empty_message ?? 'Nenhum artigo do blog foi publicado ainda.' }}</p>
                    </div>
                </article>
            @endforelse
        </div>

        @if ($posts->hasPages())
            <div class="pagination-container reveal" style="margin-top: 2rem; text-align: center;">
                {{ $posts->withQueryString()->links() }}
            </div>
        @endif
    </section>
@endsection
