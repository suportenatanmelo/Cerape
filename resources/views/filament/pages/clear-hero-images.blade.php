@php
    $can = auth()->user() && auth()->user()->hasAnyRole([\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_SUPER_ADMIN]);
@endphp

<x-filament::page>
    <x-slot name="header">
        <h2 class="text-lg font-medium">Limpar imagens do carrossel (Hero)</h2>
    </x-slot>

    @if (! $can)
        <div class="p-4 text-sm text-danger-600">Você não tem permissão para acessar esta página.</div>
    @else
        <form method="POST" action="{{ route('admin.clear-hero-images') }}">
            @csrf

            <div class="space-y-4">
                <p>Esta operação removerá os caminhos de imagens dos slides do carrossel. Recomenda-se criar backup antes.</p>

                <label class="flex items-center">
                    <input type="checkbox" name="backup" value="1" checked class="mr-2" />
                    <span>Fazer backup das entradas antes de limpar</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" name="queue" value="1" checked class="mr-2" />
                    <span>Executar em fila (recomendado)</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" name="confirm" value="1" class="mr-2" />
                    <span>Tem certeza que deseja remover as imagens do carrossel?</span>
                </label>

                <div class="pt-4">
                    <button type="submit" class="filament-button filament-button-size-md filament-button-color-danger">Confirmar e limpar</button>
                </div>
            </div>
        </form>
    @endif

</x-filament::page>
