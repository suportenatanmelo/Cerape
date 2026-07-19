@php
    $state = $getState();

    if ($state instanceof \Illuminate\Support\Collection) {
        $state = $state->toArray();
    }

    if (is_string($state)) {
        $decoded = json_decode($state, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $state = $decoded;
        }
    }

    $hasContent = filled($state);
@endphp

<div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm dark:border-gray-800 dark:bg-gray-900/60">
    @if ($hasContent)
        <pre class="overflow-x-auto whitespace-pre-wrap break-words text-xs leading-5 text-gray-700 dark:text-gray-200">{{ json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
    @else
        <div class="text-gray-500 dark:text-gray-400">Sem alterações registradas.</div>
    @endif
</div>
