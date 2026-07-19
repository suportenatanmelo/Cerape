<x-filament-widgets::widget>
    <div class="dashboard-widget-shell p-5 sm:p-6">
        <div class="dashboard-widget-header">
            <div>
                <div class="dashboard-widget-kicker">Pessoas</div>
                <h3 class="dashboard-widget-title">Aniversariantes</h3>
                <p class="dashboard-widget-subtitle">Data de nascimento celebrada hoje e nos próximos dias.</p>
            </div>
        </div>

        <div class="mt-5 grid gap-4 lg:grid-cols-2">
            <section class="space-y-4 rounded-[1.4rem] border border-gray-200/80 bg-white/90 p-4 shadow-sm dark:border-gray-800 dark:bg-gray-950/50">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="text-sm font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">Hoje</div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Aniversariantes do dia para reconhecimento imediato.</p>
                    </div>
                    <span class="dashboard-badge dashboard-badge--primary">{{ count($today) }}</span>
                </div>

                <div class="space-y-3">
                    @forelse ($today as $item)
                        <div class="flex items-center gap-3 rounded-3xl border border-gray-200/80 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-950/40">
                            <img src="{{ $item['avatar'] ? route('media.serve', ['path' => ltrim($item['avatar'], '/')]) : 'https://ui-avatars.com/api/?name=' . urlencode($item['name']) }}" class="h-11 w-11 rounded-full object-cover" alt="{{ $item['name'] }}">
                            <div class="min-w-0 flex-1">
                                <div class="truncate font-semibold text-gray-950 dark:text-white">{{ $item['name'] }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item['detail'] }} · {{ $item['age'] }} anos</div>
                            </div>
                            <span class="dashboard-badge dashboard-badge--success">{{ $item['date'] }}</span>
                        </div>
                    @empty
                        <div class="rounded-3xl border border-dashed border-gray-200/80 bg-white/60 px-4 py-4 text-sm text-gray-500 dark:border-gray-800 dark:bg-gray-950/30 dark:text-gray-400">
                            Nenhum aniversariante registrado para hoje.
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="space-y-4 rounded-[1.4rem] border border-gray-200/80 bg-white/90 p-4 shadow-sm dark:border-gray-800 dark:bg-gray-950/50">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="text-sm font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">Próxima semana</div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Visão dos aniversariantes do período de sete dias.</p>
                    </div>
                    <span class="dashboard-badge dashboard-badge--neutral">{{ count($week) }}</span>
                </div>

                <div class="space-y-3">
                    @forelse ($week as $item)
                        <div class="flex items-center gap-3 rounded-3xl border border-gray-200/80 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-950/40">
                            <img src="{{ $item['avatar'] ? route('media.serve', ['path' => ltrim($item['avatar'], '/')]) : 'https://ui-avatars.com/api/?name=' . urlencode($item['name']) }}" class="h-11 w-11 rounded-full object-cover" alt="{{ $item['name'] }}">
                            <div class="min-w-0 flex-1">
                                <div class="truncate font-semibold text-gray-950 dark:text-white">{{ $item['name'] }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item['detail'] }} · {{ $item['age'] }} anos</div>
                            </div>
                            <span class="dashboard-badge dashboard-badge--warning">{{ $item['date'] }}</span>
                        </div>
                    @empty
                        <div class="rounded-3xl border border-dashed border-gray-200/80 bg-white/60 px-4 py-4 text-sm text-gray-500 dark:border-gray-800 dark:bg-gray-950/30 dark:text-gray-400">
                            Nenhum aniversariante previsto para a semana.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-filament-widgets::widget>
