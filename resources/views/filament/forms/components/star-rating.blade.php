@php
    $fieldWrapperView = $getFieldWrapperView();
    $statePath = $getStatePath();
    $labels = [
        1 => 'Razoável',
        2 => 'Bom',
        3 => 'Muito bom',
        4 => 'Ótimo',
        5 => 'Excelente',
    ];
@endphp

<x-dynamic-component
    :component="$fieldWrapperView"
    :field="$field"
>
    <div
        x-data="{
            rating: $wire.$entangle('{{ $statePath }}'),
            labels: @js($labels),
        }"
        class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-white/5"
    >
        <div class="mb-3 flex items-center justify-between gap-3">
            <div class="text-sm font-medium text-gray-700 dark:text-gray-200">
                <span x-text="rating ? labels[Number(rating)] : 'Arraste para definir uma nota'"></span>
            </div>
            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                <span x-text="rating || 1"></span>/5
            </div>
        </div>

        <input
            type="range"
            min="1"
            max="5"
            step="1"
            x-model.number="rating"
            class="form-range h-2 w-full cursor-pointer rounded-full bg-gray-200 accent-amber-500"
            aria-label="Nota de elogio"
        />

        <div class="mt-3 flex items-center justify-between text-xs font-medium text-gray-500 dark:text-gray-400">
            <span>1</span>
            <span>2</span>
            <span>3</span>
            <span>4</span>
            <span>5</span>
        </div>

        <div class="mt-3 text-sm text-gray-600 dark:text-gray-300">
            <span class="font-semibold" x-text="rating ? labels[Number(rating)] : 'Sem nota definida'">Razoável</span>
        </div>
    </div>
</x-dynamic-component>
