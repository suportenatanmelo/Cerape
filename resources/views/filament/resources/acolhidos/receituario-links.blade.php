@php
    $files = collect($getState() ?? [])
        ->filter(fn ($file) => filled($file))
        ->values();
@endphp

<div class="space-y-2">
    @foreach ($files as $file)
        <a
            href="{{ \Illuminate\Support\Facades\Storage::disk($disk ?? 'public')->url($file) }}"
            target="_blank"
            class="inline-flex items-center gap-2 rounded-lg bg-primary-50 px-3 py-2 text-sm font-medium text-primary-700 hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-300"
        >
            <x-filament::icon icon="heroicon-o-arrow-top-right-on-square" class="h-4 w-4" />
            <span>{{ basename((string) $file) }}</span>
        </a>
    @endforeach
</div>
