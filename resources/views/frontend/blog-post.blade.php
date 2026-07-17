@extends('frontend.layout')

@section('meta_title', $post->title . ' | ' . ($settings?->brand_name ?? 'CERAPE'))
@section('meta_description', $post->excerpt)
@section('meta_image', $post->imageUrl() ?? \App\Support\SystemBranding::faviconUrl())
@section('meta_type', 'article')
@section('meta_canonical', route('blog.show', ['slug' => $post->slug]))

@section('content')
    <section class="section" style="padding-top: 8rem;">
        <div class="section-head reveal">
            <span class="eyebrow">{{ $settings?->blog_eyebrow ?? 'Conteúdo' }}</span>
            <h1>{{ $post->title }}</h1>
            <p>{{ optional($post->published_at)->format('d/m/Y') }} • {{ $post->author_name ?? 'Equipe CERAPE' }}</p>
        </div>

        <div class="blog-post-detail reveal">
            @if ($post->imageUrl())
                <div class="post-img">
                    <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" loading="lazy">
                </div>
            @endif

            <div class="post-body">
                <p>{{ $post->excerpt }}</p>
                <div>{!! $post->content !!}</div>
                @if (is_array($post->tags) ? count($post->tags) : filled($post->tags))
                    <p class="post-tags">Tags: {{ is_array($post->tags) ? implode(', ', $post->tags) : $post->tags }}</p>
                @endif
                <a class="btn btn-line" href="{{ route('home') }}">Voltar ao blog</a>
            </div>
        </div>
    </section>
@endsection
