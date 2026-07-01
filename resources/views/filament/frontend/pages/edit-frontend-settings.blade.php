<x-filament-panels::page>
    <div class="rounded-3xl border border-[--gray-200] bg-white p-6 shadow-sm">
        <h2 class="text-2xl font-semibold tracking-tight text-gray-950">Configuração do site</h2>
        <p class="mt-2 text-sm text-gray-600">
            Ajuste aqui o conteúdo público do site sem usar modais ou listas intermediárias.
        </p>

        <div class="mt-6">
            {{ $this->form }}
        </div>

        <div class="mt-6 flex justify-end">
            <x-filament::button wire:click="save" color="warning">
                Salvar
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
