<x-filament-widgets::widget>
    <div class="overflow-hidden rounded-[2.4rem] border border-neutral-900 bg-[#f7f4ee] text-neutral-950 shadow-[0_28px_80px_rgba(0,0,0,0.10)]">
        <div class="border-b border-neutral-900 px-6 py-6">
            <p class="text-[11px] font-semibold uppercase tracking-[0.34em] text-neutral-500">
                Dashboard da familia
            </p>
            <div class="mt-2 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="font-serif text-3xl leading-tight text-neutral-950">
                        {{ $gallery?->titulo ?: 'Galeria do acolhido' }}
                    </h2>
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-neutral-700">
                        {{ $gallery?->descricao ?: 'Clique nas imagens para ampliar, navegar, dar zoom e visualizar em tela cheia.' }}
                    </p>
                    @if (filled($gallery?->lastGalleryUpdateLabel()))
                        <p class="mt-3 text-[11px] font-semibold uppercase tracking-[0.24em] text-neutral-500">
                            Ultima atualizacao: {{ $gallery?->lastGalleryUpdateLabel() }}
                        </p>
                    @endif
                </div>
                <div class="rounded-full border border-neutral-900 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-neutral-700">
                    {{ count($imageUrls) }} imagens de {{ $acolhido?->nome_completo_paciente }}
                </div>
            </div>
        </div>

        <div class="space-y-8 px-6 py-6">
            @foreach ($galleryTimeline as $galleryGroup)
                <section class="space-y-4">
                    <div class="flex items-center justify-between gap-3 border-b border-dashed border-neutral-400 pb-3">
                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-[0.28em] text-neutral-500">
                                {{ $galleryGroup['label'] }}
                            </h3>
                            <p class="mt-1 text-sm text-neutral-600">
                                {{ count($galleryGroup['images']) }} imagem(ns) adicionada(s) nesta data
                            </p>
                        </div>

                        @php
                            $galleryId = 'family-gallery-' . str_replace(['{', '}', '-'], '', (string) \Illuminate\Support\Str::uuid());
                        @endphp

                        <div class="hidden items-center gap-2 sm:flex">
                            <button
                                type="button"
                                class="rounded-full border border-neutral-900 px-3 py-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-neutral-700 transition hover:bg-neutral-900 hover:text-white"
                                onclick="document.getElementById('{{ $galleryId }}').scrollBy({ left: -320, behavior: 'smooth' })"
                            >
                                Prev
                            </button>
                            <button
                                type="button"
                                class="rounded-full border border-neutral-900 px-3 py-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-neutral-700 transition hover:bg-neutral-900 hover:text-white"
                                onclick="document.getElementById('{{ $galleryId }}').scrollBy({ left: 320, behavior: 'smooth' })"
                            >
                                Next
                            </button>
                        </div>
                    </div>

                    <div
                        id="{{ $galleryId }}"
                        class="image-gallery family-gallery-dashboard flex snap-x snap-mandatory gap-5 overflow-x-auto pb-2 pr-2"
                        data-viewer-gallery
                    >
                        @foreach ($galleryGroup['images'] as $image)
                            <figure class="w-[16.5rem] shrink-0 snap-start overflow-hidden rounded-[1.75rem] border border-neutral-900 bg-white shadow-[0_18px_40px_rgba(0,0,0,0.08)]">
                                <img
                                    src="{{ $image['url'] }}"
                                    alt="{{ $image['caption'] }}"
                                    loading="lazy"
                                    class="h-56 w-full cursor-zoom-in object-cover grayscale transition duration-500 hover:scale-[1.02] hover:grayscale-0"
                                    data-added-at="{{ $image['added_at'] }}"
                                />
                                <figcaption class="border-t border-neutral-900 px-4 py-3 font-serif text-sm italic text-neutral-700">
                                    {{ $image['caption'] }}
                                </figcaption>
                            </figure>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </div>
</x-filament-widgets::widget>
