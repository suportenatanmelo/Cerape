<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h2 class="text-lg font-semibold">Detalhes da auditoria</h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Visualize o evento completo, os valores antes/depois e as informações de requisição.</p>
        </div>

        @php($record = $this->record)

        <div class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-500">Resumo</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Evento</dt><dd class="font-medium">{{ $record->event }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Status</dt><dd class="font-medium">{{ $record->status }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Recurso</dt><dd class="font-medium">{{ $record->resource }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Ação</dt><dd class="font-medium">{{ $record->action }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Data</dt><dd class="font-medium">{{ $record->created_at?->format('d/m/Y H:i') }}</dd></div>
                </dl>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-500">Usuário</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Nome</dt><dd class="font-medium">{{ $record->user?->name ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">E-mail</dt><dd class="font-medium">{{ $record->user?->email ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">IP</dt><dd class="font-medium">{{ $record->ip ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">User Agent</dt><dd class="font-medium break-all">{{ $record->user_agent ?? '-' }}</dd></div>
                </dl>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-500">Antes</h3>
                <pre class="max-h-96 overflow-auto rounded-lg bg-gray-950 p-4 text-xs text-gray-100">{{ json_encode($record->old_values ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-500">Depois</h3>
                <pre class="max-h-96 overflow-auto rounded-lg bg-gray-950 p-4 text-xs text-gray-100">{{ json_encode($record->new_values ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
    </div>
</x-filament-panels::page>
