@extends('frontend.layout')

@section('meta_title', $title . ' | ' . ($settings?->brand_name ?? 'CERAPE'))
@section('meta_description', 'Listagem de ' . mb_strtolower($title) . ' gerenciada pelo painel de CMS da CERAPE.')
@section('meta_type', 'website')
@section('meta_canonical', request()->url())

@section('content')
    <section class="section" style="padding-top: 8rem;">
        <div class="section-head reveal">
            <span class="eyebrow">{{ $settings?->brand_name ?? 'CERAPE' }}</span>
            <h1>{{ $title }}</h1>
            <p>Conteúdo administrado pelo painel Filament.</p>
        </div>

        <div class="blog-grid">
            @forelse ($items as $item)
                <article class="post-card reveal">
                    @if ($item->imageUrl())
                        <div class="post-img">
                            <img src="{{ $item->imageUrl() }}" alt="{{ $item->title }}" loading="lazy">
                        </div>
                    @endif
                    <div class="post-body">
                        <div class="post-meta">
                            <span class="post-tag">{{ $item->category ?: $item->typeLabel() }}</span>
                            @if ($published = optional($item->published_at)->format('d/m/Y'))
                                <span>{{ $published }}</span>
                            @endif
                        </div>
                        <h2>
                            @php
                                $itemUrl = null;
                                if ($item->slug) {
                                    if ($item->type === \App\Models\CmsContent::TYPE_NEWS) {
                                        $itemUrl = route('news.show', ['slug' => $item->slug]);
                                    } elseif ($item->type === \App\Models\CmsContent::TYPE_EVENT) {
                                        $itemUrl = route('events.show', ['slug' => $item->slug]);
                                    }
                                }
                            @endphp

                            @if ($itemUrl)
                                <a href="{{ $itemUrl }}">{{ $item->title }}</a>
                            @else
                                {{ $item->title }}
                            @endif
                        </h2>
                        @if ($item->subtitle)
                            <p><strong>{{ $item->subtitle }}</strong></p>
                        @endif
                        <p>{{ $item->summary }}</p>
                        @if ($item->content)
                            <div>{!! \Illuminate\Support\Str::limit(strip_tags($item->content), 220) !!}</div>
                        @endif
                        @if ($itemUrl)
                            <a class="btn btn-line" href="{{ $itemUrl }}">Leia mais</a>
                        @elseif ($item->cta_url)
                            <a class="btn btn-line" href="{{ $item->cta_url }}">{{ $item->cta_label ?: 'Saiba mais' }}</a>
                        @endif
                    </div>
                </article>
            @empty
                <article class="post-card">
                    <div class="post-body">
                        <p>Nenhum conteúdo publicado no momento.</p>
                    </div>
                </article>
            @endforelse
        </div>

        @if ($items->hasPages())
            <div class="pagination-container reveal" style="margin-top: 2rem; text-align: center;">
                {{ $items->withQueryString()->links() }}
            </div>
        @endif
    </section>
@endsection
