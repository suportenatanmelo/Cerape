<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Paletas de temas</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Escolha uma das paletas abaixo para aplicar ao painel administrativo.</p>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($palettes as $palette)
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $palette->name }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $palette->slug }}</p>
                    </div>
                    @if ($activePalette && $activePalette->id === $palette->id)
                        <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-400">
                            Ativo
                        </span>
                    @endif
                </div>

                <div class="mb-4 flex h-14 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex-1" style="background-color: {{ $palette->primary_color }}"></div>
                    <div class="flex-1" style="background-color: {{ $palette->secondary_color }}"></div>
                    <div class="flex-1" style="background-color: {{ $palette->accent_color }}"></div>
                </div>

                <div class="space-y-1 text-sm text-gray-600 dark:text-gray-400">
                    <div><span class="font-medium">Primária:</span> {{ $palette->primary_color }}</div>
                    <div><span class="font-medium">Secundária:</span> {{ $palette->secondary_color }}</div>
                    <div><span class="font-medium">Destaque:</span> {{ $palette->accent_color }}</div>
                </div>

                <div class="mt-4">
                    <form method="POST" action="{{ route('filament.admin.pages.theme-palettes.apply-theme', ['id' => $palette->id]) }}">
                        @csrf
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-primary-700">
                            Aplicar tema
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
