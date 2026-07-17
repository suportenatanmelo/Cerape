@php
    $heading = $this->getHeading();
@endphp

<x-filament-panels::page>
    <form wire:submit="submit" class="space-y-6">
        @if (filled($heading))
            <x-filament::section.heading>{{ $heading }}</x-filament::section.heading>
        @endif

        {{ $this->form }}

        <div class="flex justify-end gap-3">
            <x-filament::button
                type="button"
                color="gray"
                wire:click="redirect('{{ url()->previous() }}')"
            >
                Cancelar
            </x-filament::button>
            <x-filament::button type="submit" color="success">
                Enviar Formulário
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>

