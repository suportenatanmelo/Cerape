<div class="w-full rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Resumo operacional</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Indicadores principais</p>
        </div>
    </div>

    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($stats as $stat)
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-900/40">
                <div class="flex items-center justify-between">
                    <div class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $stat['label'] }}</div>
                    <div class="rounded-full bg-gray-100 p-2 text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                        <x-filament::icon :name="$stat['icon']" class="h-4 w-4" />
                    </div>
                </div>
                <div class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stat['value'] }}</div>
                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $stat['description'] }}</div>
            </div>
        @endforeach
    </div>
</div>
