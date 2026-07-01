@extends('frontend.layout')

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
                        <span class="post-tag">{{ $item->category ?: $item->typeLabel() }}</span>
                        <h2>{{ $item->title }}</h2>
                        @if ($item->subtitle)
                            <p><strong>{{ $item->subtitle }}</strong></p>
                        @endif
                        <p>{{ $item->summary }}</p>
                        @if ($item->content)
                            <div>{!! $item->content !!}</div>
                        @endif
                        @if ($item->cta_url)
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
    </section>
@endsection
