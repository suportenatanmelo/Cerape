<x-filament-panels::page>
    <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
        <div class="rounded-3xl border border-[--gray-200] bg-white p-6 shadow-sm">
            <h2 class="text-2xl font-semibold tracking-tight text-gray-950">Editar seção Quem somos</h2>
            <p class="mt-2 text-sm text-gray-600">
                Aqui você ajusta o texto, o título e a imagem usados na seção “Quem somos” da página principal.
            </p>

            <div class="mt-6">
                {{ $this->form }}
            </div>

            <div class="mt-6 flex justify-end">
                <x-filament::button wire:click="save" color="warning">
                    Salvar
                </x-filament::button>
            </div>
        </div>

        <div class="overflow-hidden rounded-3xl border border-[--gray-200] bg-gradient-to-b from-amber-50 to-white p-6 shadow-sm">
            <div class="mb-4">
                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.16em] text-amber-800">Prévia</span>
                <h3 class="mt-3 text-2xl font-semibold tracking-tight text-gray-950">{{ $data['about_title'] ?? 'Sobre a CERAPE' }}</h3>
            </div>

            @php
                $aboutImagePath = is_array($data['about_image_path'] ?? null)
                    ? collect($data['about_image_path'])->filter()->first()
                    : ($data['about_image_path'] ?? null);
                $aboutVideoUrl = trim((string) ($data['about_video_url'] ?? ''));
                $aboutVideoWidth = (int) ($data['about_video_width'] ?? 560);
                $aboutVideoHeight = (int) ($data['about_video_height'] ?? 315);
                $aboutVideoEmbedUrl = null;

                if ($aboutVideoUrl !== '') {
                    if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{11})~', $aboutVideoUrl, $matches)) {
                        $aboutVideoEmbedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                    }
                }
            @endphp

            @if (!empty($aboutImagePath))
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($aboutImagePath) }}" alt="Prévia Quem somos" class="mb-4 h-72 w-full rounded-2xl object-cover">
            @else
                <div class="mb-4 flex h-72 items-center justify-center rounded-2xl border border-dashed border-amber-200 bg-white text-sm text-gray-500">
                    Nenhuma imagem selecionada ainda.
                </div>
            @endif

            @if (!empty($aboutVideoEmbedUrl))
                <div class="mb-4 overflow-hidden rounded-2xl border border-amber-200 bg-white shadow-sm">
                    <iframe
                        width="{{ $aboutVideoWidth }}"
                        height="{{ $aboutVideoHeight }}"
                        src="{{ $aboutVideoEmbedUrl }}"
                        title="Vídeo Quem somos"
                        class="block w-full"
                        loading="lazy"
                        referrerpolicy="strict-origin-when-cross-origin"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen
                    ></iframe>
                </div>
            @endif

            <div class="space-y-4 text-sm leading-7 text-gray-700">
                <p>{!! $data['about_paragraph_one'] ?? 'A CERAPE é uma casa de recuperação dedicada a oferecer acolhimento, tratamento e um novo começo para quem enfrenta a dependência química.' !!}</p>
                <p>{!! $data['about_paragraph_two'] ?? 'Acreditamos que a recuperação acontece em comunidade: por isso trabalhamos junto às famílias, com transparência e respeito ao tempo de cada pessoa, do primeiro dia até a reinserção social.' !!}</p>
            </div>
        </div>
    </div>
</x-filament-panels::page>
