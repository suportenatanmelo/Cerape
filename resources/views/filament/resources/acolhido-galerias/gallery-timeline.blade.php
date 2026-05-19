@php
    $context = $context ?? 'admin';
    $isFamily = $context === 'family';
    $wrapperClasses = $isFamily
        ? 'family-gallery-shell overflow-hidden rounded-[2rem] border border-[#1f2937] bg-[linear-gradient(180deg,#f8f4ec_0%,#f3ede3_100%)] text-neutral-950 shadow-[0_30px_90px_rgba(15,23,42,0.14)]'
        : 'overflow-hidden rounded-[2rem] border border-slate-200 bg-white text-slate-950 shadow-[0_20px_60px_rgba(15,23,42,0.08)]';
    $headerBorder = $isFamily ? 'border-neutral-900' : 'border-slate-200';
    $eyebrowClass = $isFamily
        ? 'text-[11px] font-semibold uppercase tracking-[0.34em] text-neutral-500'
        : 'text-[11px] font-semibold uppercase tracking-[0.34em] text-slate-500';
    $titleClass = $isFamily
        ? 'font-serif text-[2.15rem] leading-[1.05] text-neutral-950'
        : 'font-serif text-3xl leading-tight text-slate-950';
    $descriptionClass = $isFamily
        ? 'mt-3 max-w-2xl text-[0.95rem] leading-7 text-neutral-700'
        : 'mt-2 max-w-2xl text-sm leading-relaxed text-slate-600';
    $badgeClass = $isFamily
        ? 'rounded-full border border-neutral-900 bg-white/70 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-neutral-700 shadow-sm backdrop-blur'
        : 'rounded-full border border-slate-300 bg-slate-50 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-700';
    $sectionRuleClass = $isFamily ? 'border-neutral-400' : 'border-slate-300';
    $sectionTitleClass = $isFamily
        ? 'text-sm font-semibold uppercase tracking-[0.28em] text-neutral-500'
        : 'text-sm font-semibold uppercase tracking-[0.28em] text-slate-500';
    $sectionDescriptionClass = $isFamily ? 'mt-1 text-sm text-neutral-600' : 'mt-1 text-sm text-slate-600';
    $buttonClass = $isFamily
        ? 'rounded-full border border-neutral-900 bg-white/75 px-3 py-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-neutral-700 transition hover:-translate-y-0.5 hover:bg-neutral-900 hover:text-white'
        : 'rounded-full border border-slate-300 bg-white px-3 py-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-700 transition hover:border-slate-900 hover:bg-slate-900 hover:text-white';
    $figureClass = $isFamily
        ? 'family-gallery-card w-[18.75rem] shrink-0 snap-start overflow-hidden rounded-[1.45rem] border border-[#111827] bg-white shadow-[0_22px_44px_rgba(15,23,42,0.14)]'
        : 'w-[16.5rem] shrink-0 snap-start overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-[0_16px_36px_rgba(15,23,42,0.10)]';
    $captionClass = $isFamily
        ? 'border-t border-neutral-900/80 bg-[#fffdf8] px-4 py-3 font-serif text-sm italic text-neutral-700'
        : 'border-t border-slate-200 px-4 py-3 font-serif text-sm italic text-slate-600';
@endphp

@once
    @push('styles')
        <style>
            .family-gallery-shell {
                position: relative;
            }

            .family-gallery-shell::before {
                background:
                    radial-gradient(circle at top right, rgba(217, 119, 6, 0.10), transparent 22rem),
                    radial-gradient(circle at left top, rgba(15, 118, 110, 0.12), transparent 20rem);
                content: "";
                inset: 0;
                pointer-events: none;
                position: absolute;
            }

            .family-gallery-card {
                transition: transform 220ms ease, box-shadow 220ms ease, filter 220ms ease;
            }

            .family-gallery-card:hover {
                box-shadow: 0 28px 56px rgba(15, 23, 42, 0.18);
                transform: translateY(-4px);
            }
        </style>
    @endpush
@endonce

<div class="{{ $wrapperClasses }}">
    <div class="relative border-b {{ $headerBorder }} px-6 py-6 sm:px-8">
        <p class="{{ $eyebrowClass }}">
            {{ $isFamily ? 'Dashboard da familia' : 'Dashboard institucional' }}
        </p>
        <div class="mt-3 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <h2 class="{{ $titleClass }}">
                    {{ $gallery?->titulo ?: 'Galeria do acolhido' }}
                </h2>
                <p class="{{ $descriptionClass }}">
                    {{ $gallery?->descricao ?: 'Clique nas imagens para ampliar, navegar, dar zoom e visualizar em tela cheia.' }}
                </p>
                @if (filled($gallery?->lastGalleryUpdateLabel()))
                    <p class="mt-3 text-[11px] font-semibold uppercase tracking-[0.24em] {{ $isFamily ? 'text-neutral-500' : 'text-slate-500' }}">
                        Ultima atualizacao: {{ $gallery?->lastGalleryUpdateLabel() }}
                    </p>
                @endif
            </div>
            <div class="{{ $badgeClass }}">
                {{ count($imageUrls ?? []) }} imagens de {{ $acolhido?->nome_completo_paciente }}
            </div>
        </div>
    </div>

    <div class="relative space-y-8 px-6 py-6 sm:px-8">
        @forelse (($galleryTimeline ?? []) as $galleryGroup)
            <section class="space-y-4">
                <div class="flex items-center justify-between gap-3 border-b border-dashed {{ $sectionRuleClass }} pb-3">
                    <div>
                        <h3 class="{{ $sectionTitleClass }}">
                            {{ $galleryGroup['label'] }}
                        </h3>
                        <p class="{{ $sectionDescriptionClass }}">
                            {{ count($galleryGroup['images']) }} imagem(ns) adicionada(s) nesta data
                        </p>
                    </div>

                    @php
                        $galleryId = 'gallery-timeline-' . str_replace(['{', '}', '-'], '', (string) \Illuminate\Support\Str::uuid());
                    @endphp

                    <div class="hidden items-center gap-2 sm:flex">
                        <button
                            type="button"
                            class="{{ $buttonClass }}"
                            onclick="document.getElementById('{{ $galleryId }}').scrollBy({ left: -320, behavior: 'smooth' })"
                        >
                            Prev
                        </button>
                        <button
                            type="button"
                            class="{{ $buttonClass }}"
                            onclick="document.getElementById('{{ $galleryId }}').scrollBy({ left: 320, behavior: 'smooth' })"
                        >
                            Next
                        </button>
                    </div>
                </div>

                <div
                    id="{{ $galleryId }}"
                    class="image-gallery family-gallery-dashboard flex snap-x snap-mandatory gap-5 overflow-x-auto pb-3 pr-2"
                    data-viewer-gallery
                >
                    @foreach ($galleryGroup['images'] as $image)
                        <figure class="{{ $figureClass }}">
                            <div class="relative aspect-video overflow-hidden bg-neutral-900">
                                <div class="absolute inset-x-0 top-0 z-[1] h-20 bg-gradient-to-b from-black/28 to-transparent"></div>
                                <img
                                    src="{{ $image['url'] }}"
                                    alt="{{ $image['caption'] }}"
                                    loading="lazy"
                                    class="h-full w-full cursor-zoom-in object-cover transition duration-500 hover:scale-[1.04]"
                                    data-added-at="{{ $image['added_at'] }}"
                                />
                                <div class="absolute left-3 top-3 z-[2] rounded-full bg-white/88 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.22em] text-neutral-800 shadow-sm">
                                    CERAPE CRC
                                </div>
                            </div>
                            <figcaption class="{{ $captionClass }}">
                                {{ $image['caption'] }}
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </section>
        @empty
            <div class="rounded-[1.5rem] border border-dashed {{ $isFamily ? 'border-neutral-400 bg-white/70 text-neutral-600' : 'border-slate-300 bg-slate-50 text-slate-600' }} px-6 py-10 text-center">
                Nenhuma imagem disponivel nesta galeria.
            </div>
        @endforelse
    </div>
</div>
