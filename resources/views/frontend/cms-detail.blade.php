@extends('frontend.layout')

@section('meta_title', $item->meta_title ?: $item->title . ' | ' . ($settings?->brand_name ?? 'CERAPE'))
@section('meta_description', $item->meta_description ?: $item->summary ?: ($settings?->brand_name ?? 'CERAPE'))
@section('meta_image', $item->imageUrl() ?: ($item->og_image_path ? $item->imageUrl('og_image_path') : \App\Support\SystemBranding::faviconUrl()))
@section('meta_type', 'article')
@section('meta_canonical', $item->canonical_url ?: request()->url())

@section('content')
    <section class="section" style="padding-top: 8rem;">
        <div class="section-head reveal">
            <span class="eyebrow">{{ $item->category ?: $item->typeLabel() }}</span>
            <h1>{{ $item->title }}</h1>
            @if ($item->subtitle)
                <p><strong>{{ $item->subtitle }}</strong></p>
            @endif
        </div>

        <div class="blog-post-detail reveal">
            @if ($item->imageUrl())
                <div class="post-img">
                    <img src="{{ $item->imageUrl() }}" alt="{{ $item->title }}" loading="lazy">
                </div>
            @endif

            <div class="post-body">
                <p>{{ $item->summary }}</p>
                <div>{!! $item->content !!}</div>
                @if (is_array($item->tags) ? count($item->tags) : filled($item->tags))
                    <p class="post-tags">Tags: {{ is_array($item->tags) ? implode(', ', $item->tags) : $item->tags }}</p>
                @endif
                <a class="btn btn-line" href="{{ back()->getTargetUrl() ?: route('home') }}">Voltar</a>
            </div>
        </div>
    </section>
@endsection
