<x-filament-widgets::widget>
    <div class="dashboard-widget-shell p-5 sm:p-6">
        <div class="dashboard-widget-header">
            <div>
                <div class="dashboard-widget-kicker">Vínculos</div>
                <h3 class="dashboard-widget-title">Aniversariantes</h3>
                <p class="dashboard-widget-subtitle">Pessoas do mês em um formato mais direto e fácil de escanear.</p>
            </div>
        </div>

        <div class="mt-5 grid gap-4">
            <section class="space-y-3">
                <div class="flex items-center justify-between gap-3">
                    <h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">Acolhidos</h4>
                    <span class="dashboard-badge dashboard-badge--neutral">{{ count($acolhidos) }}</span>
                </div>

                <div class="space-y-3">
                    @forelse ($acolhidos as $item)
                        <div class="flex items-center gap-3 rounded-[1.2rem] border border-gray-200/80 bg-white/80 px-4 py-3 dark:border-gray-800 dark:bg-gray-950/40">
                            <img src="{{ $item->avatar ? route('media.serve', ['path' => $item->avatar]) : 'https://ui-avatars.com/api/?name=' . urlencode($item->nome_completo_paciente) }}" class="h-11 w-11 rounded-full object-cover ring-2 ring-white dark:ring-gray-900" alt="{{ $item->nome_completo_paciente }}">
                            <div class="min-w-0 flex-1">
                                <div class="truncate font-semibold text-gray-950 dark:text-white">{{ $item->nome_completo_paciente }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item->data_nascimento?->age }} anos</div>
                            </div>
                            <span class="dashboard-badge dashboard-badge--success">Acolhido</span>
                        </div>
                    @empty
                        <div class="rounded-[1.2rem] border border-dashed border-gray-200/80 bg-white/60 px-4 py-3 text-sm text-gray-500 dark:border-gray-800 dark:bg-gray-950/30 dark:text-gray-400">
                            Nenhum acolhido aniversariante neste mês.
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="space-y-3">
                <div class="flex items-center justify-between gap-3">
                    <h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">Equipe</h4>
                    <span class="dashboard-badge dashboard-badge--neutral">{{ count($funcionarios) }}</span>
                </div>

                <div class="space-y-3">
                    @forelse ($funcionarios as $item)
                        <div class="flex items-center gap-3 rounded-[1.2rem] border border-gray-200/80 bg-white/80 px-4 py-3 dark:border-gray-800 dark:bg-gray-950/40">
                            <img src="{{ $item->filament_avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($item->name) }}" class="h-11 w-11 rounded-full object-cover ring-2 ring-white dark:ring-gray-900" alt="{{ $item->name }}">
                            <div class="min-w-0 flex-1">
                                <div class="truncate font-semibold text-gray-950 dark:text-white">{{ $item->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item->funcao_usuario ?? 'Funcionário' }}</div>
                            </div>
                            <span class="dashboard-badge dashboard-badge--warning">Equipe</span>
                        </div>
                    @empty
                        <div class="rounded-[1.2rem] border border-dashed border-gray-200/80 bg-white/60 px-4 py-3 text-sm text-gray-500 dark:border-gray-800 dark:bg-gray-950/30 dark:text-gray-400">
                            Nenhum funcionário aniversariante neste mês.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-filament-widgets::widget>
