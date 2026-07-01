<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Alertas</x-slot>

        <div class="space-y-3">
            @foreach ($items as $item)
                @php
                    $alertColors = [
                        'danger' => '#ef4444',
                        'warning' => '#f59e0b',
                        'primary' => '#3b82f6',
                        'gray' => '#6b7280',
                        'success' => '#22c55e',
                    ];
                    $dotColor = $alertColors[$item['color']] ?? '#6b7280';
                @endphp
                <div class="flex items-center justify-between rounded-xl border border-gray-200 px-4 py-3 dark:border-gray-700">
                    <div>
                        <div class="font-medium text-gray-950 dark:text-white">{{ $item['label'] }}</div>
                        <div class="text-sm text-gray-500">{{ $item['value'] }}</div>
                    </div>
                    <div class="h-3 w-3 rounded-full" style="background-color: {{ $dotColor }}"></div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
