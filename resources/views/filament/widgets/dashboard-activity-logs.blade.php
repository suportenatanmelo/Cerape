<div class="dashboard-widget-shell p-5 sm:p-6">
    <div class="dashboard-widget-header">
        <div>
            <div class="dashboard-widget-kicker">Auditoria</div>
            <h3 class="dashboard-widget-title">Últimos registros</h3>
            <p class="dashboard-widget-subtitle">Histórico consolidado das ações mais recentes no painel administrativo.</p>
        </div>

        <a href="{{ $indexUrl }}" class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:border-primary-300 hover:text-primary-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/30 dark:border-gray-700 dark:bg-gray-900/70 dark:text-gray-200">
            <x-filament::icon icon="heroicon-o-clipboard-document-list" class="h-4 w-4" />
            Abrir auditoria
        </a>
    </div>

    <div class="mt-5 grid gap-3 sm:grid-cols-2">
        <div class="dashboard-summary-card">
            <div class="dashboard-summary-label">Hoje</div>
            <div class="dashboard-summary-value">{{ $todayCount }}</div>
            <div class="dashboard-summary-note">ações registradas</div>
        </div>
        <div class="dashboard-summary-card">
            <div class="dashboard-summary-label">Cobertura</div>
            <div class="dashboard-summary-value text-[1.5rem]">Usuário, módulo e contexto</div>
            <div class="dashboard-summary-note">Login, logout e alterações relevantes centralizadas.</div>
        </div>
    </div>

    <div class="dashboard-table-shell mt-5">
        <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-800">
            <thead class="text-left text-xs uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">Quando</th>
                    <th class="px-4 py-3">Módulo</th>
                    <th class="px-4 py-3">Ação</th>
                    <th class="px-4 py-3">Usuário</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white/80 dark:divide-gray-800 dark:bg-gray-950/25">
                @forelse ($recent as $item)
                    <tr>
                        <td class="whitespace-nowrap px-4 py-3 text-gray-600 dark:text-gray-300">
                            {{ $item->executed_at?->format('d/m/Y H:i') ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="dashboard-badge dashboard-badge--neutral">
                                {{ $item->module }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $item->action }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $item->user?->name ?? 'Sistema' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">Nenhum log registrado ainda.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
