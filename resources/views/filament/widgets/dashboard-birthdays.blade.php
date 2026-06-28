<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Aniversariantes</x-slot>
        <div class="space-y-3">
            @forelse ($acolhidos as $item)
                <div class="flex items-center gap-3 rounded-xl border border-gray-200 px-4 py-3 dark:border-gray-700">
                    <img src="{{ $item->avatar ? route('media.serve', ['path' => $item->avatar]) : 'https://ui-avatars.com/api/?name=' . urlencode($item->nome_completo_paciente) }}" class="h-10 w-10 rounded-full object-cover" alt="{{ $item->nome_completo_paciente }}">
                    <div class="flex-1">
                        <div class="font-medium text-gray-950 dark:text-white">{{ $item->nome_completo_paciente }}</div>
                        <div class="text-sm text-gray-500">{{ $item->data_nascimento?->age }} anos • Acolhido</div>
                    </div>
                </div>
            @empty
                <div class="text-sm text-gray-500">Nenhum acolhido aniversariante neste mês.</div>
            @endforelse

            @forelse ($funcionarios as $item)
                <div class="flex items-center gap-3 rounded-xl border border-gray-200 px-4 py-3 dark:border-gray-700">
                    <img src="{{ $item->filament_avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($item->name) }}" class="h-10 w-10 rounded-full object-cover" alt="{{ $item->name }}">
                    <div class="flex-1">
                        <div class="font-medium text-gray-950 dark:text-white">{{ $item->name }}</div>
                        <div class="text-sm text-gray-500">{{ $item->funcao_usuario ?? 'Funcionário' }}</div>
                    </div>
                </div>
            @empty
                <div class="text-sm text-gray-500">Nenhum funcionário aniversariante neste mês.</div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
