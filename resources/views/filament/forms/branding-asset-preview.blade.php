@props([
    'label',
    'url' => null,
    'alt' => 'Arquivo de marca',
    'emptyMessage' => 'Nenhum arquivo salvo.',
    'compact' => false,
])

<div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
    <div class="text-sm font-medium text-gray-950">{{ $label }}</div>

    @if ($url)
        <div class="mt-3">
            <img
                src="{{ $url }}"
                alt="{{ $alt }}"
                class="{{ $compact ? 'h-12 w-12 rounded-xl object-contain bg-white p-2 shadow-sm' : 'max-h-28 w-full rounded-xl bg-white p-3 object-contain shadow-sm' }}"
            >
        </div>

        <div class="mt-3 text-xs text-gray-500 break-all">
            {{ $url }}
        </div>
    @else
        <div class="mt-3 text-sm text-gray-500">
            {{ $emptyMessage }}
        </div>
    @endif
</div>
