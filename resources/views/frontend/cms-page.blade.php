@extends('frontend.layout')

@section('meta_title', $page->seo?->meta_title ?: $page->title . ' | ' . ($settings?->brand_name ?? 'CERAPE'))
@section('meta_description', $page->seo?->meta_description ?: $page->summary ?: ($settings?->brand_name ?? 'CERAPE'))
@section('meta_image', $page->seo?->open_graph['image'] ?? asset('favicon/favicon-32x32.png'))
@section('meta_type', 'article')
@section('meta_canonical', $page->seo?->canonical ?: request()->url())

@section('content')
    <section class="section" style="padding-top: 8rem;">
        <div class="section-head reveal">
            <span class="eyebrow">{{ $page->title }}</span>
            <h1>{{ $page->title }}</h1>
            @if ($page->summary)
                <p>{{ $page->summary }}</p>
            @endif
        </div>

        <div class="page-content reveal">
            @foreach ($page->blocks as $block)
                <div class="cms-block cms-block-{{ str_replace('_', '-', $block->type) }}">
                    @if (filled($block->config['heading']))
                        <h2>{{ $block->config['heading'] }}</h2>
                    @endif
                    @if (filled($block->config['content']))
                        <div>{!! $block->config['content'] !!}</div>
                    @endif
                    @if (filled($block->config['button_url']))
                        <a class="btn btn-line" href="{{ $block->config['button_url'] }}">{{ $block->config['button_label'] ?? 'Saiba mais' }}</a>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
@endsection
