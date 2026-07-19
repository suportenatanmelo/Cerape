<div class="rounded-3xl border border-gray-200 bg-white/90 p-5 shadow-sm dark:border-gray-800 dark:bg-gray-950/70">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-gray-500 dark:text-gray-400">Auditoria</p>
            <h3 class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">Últimos registros</h3>
        </div>

        <a href="{{ $indexUrl }}" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-primary-500">
            <x-filament::icon icon="heroicon-o-clipboard-document-list" class="h-4 w-4" />
            Abrir auditoria
        </a>
    </div>

    <div class="mt-4 grid gap-3 sm:grid-cols-2">
        <div class="rounded-2xl bg-primary-50 p-4 dark:bg-primary-500/10">
            <div class="text-xs font-medium uppercase tracking-[0.2em] text-primary-700 dark:text-primary-300">Hoje</div>
            <div class="mt-2 text-2xl font-bold text-primary-900 dark:text-primary-100">{{ $todayCount }}</div>
            <div class="text-sm text-primary-700/80 dark:text-primary-200/80">ações registradas</div>
        </div>
        <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-900/60">
            <div class="text-xs font-medium uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400">Cobertura</div>
            <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">Usuário, módulo e contexto</div>
            <div class="text-sm text-gray-600 dark:text-gray-300">Login, logout e alterações relevantes centralizadas.</div>
        </div>
    </div>

    <div class="mt-5 overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-800">
        <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-800">
            <thead class="bg-gray-50 text-left text-xs uppercase tracking-[0.18em] text-gray-500 dark:bg-gray-900/60 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">Quando</th>
                    <th class="px-4 py-3">Módulo</th>
                    <th class="px-4 py-3">Ação</th>
                    <th class="px-4 py-3">Usuário</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white dark:divide-gray-800 dark:bg-gray-950/40">
                @forelse ($recent as $item)
                    <tr>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                            {{ $item->executed_at?->format('d/m/Y H:i') ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full bg-info-50 px-3 py-1 text-xs font-semibold text-info-700 dark:bg-info-500/10 dark:text-info-200">
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
