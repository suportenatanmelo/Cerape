<x-filament-widgets::widget>
    <div class="dashboard-widget-shell p-5 sm:p-6">
        <div class="dashboard-widget-header">
            <div>
                <div class="dashboard-widget-kicker">Operação</div>
                <h3 class="dashboard-widget-title">Ações rápidas</h3>
                <p class="dashboard-widget-subtitle">Atalhos diretos para os fluxos mais usados do painel.</p>
            </div>
        </div>

        <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($items as $item)
                <a href="{{ $item['url'] }}" class="dashboard-action-card group" aria-label="{{ $item['label'] }}">
                    <div class="flex items-start justify-between gap-3">
                        <span class="dashboard-action-icon">
                            <x-filament::icon :icon="$item['icon']" class="h-5 w-5" />
                        </span>
                        <span class="text-xs font-medium uppercase tracking-[0.18em] text-gray-400 transition group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300">
                            Abrir
                        </span>
                    </div>

                    <div class="mt-4 text-sm font-semibold text-gray-950 transition group-hover:text-primary-700 dark:text-white dark:group-hover:text-primary-300">
                        {{ $item['label'] }}
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-filament-widgets::widget>
