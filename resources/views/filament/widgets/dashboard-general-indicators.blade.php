<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Indicadores Gerais e Estatísticas Complementares</x-slot>
        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-6">
            <div class="rounded-xl border p-4 dark:border-gray-700">
                <div class="text-sm text-gray-500">Taxa de Ocupação</div>
                <div class="text-2xl font-semibold">{{ $taxaOcupacao }}%</div>
            </div>
            <div class="rounded-xl border p-4 dark:border-gray-700">
                <div class="text-sm text-gray-500">Média de Permanência</div>
                <div class="text-2xl font-semibold">{{ $mediaPermanencia }} dias</div>
            </div>
            <div class="rounded-xl border p-4 dark:border-gray-700">
                <div class="text-sm text-gray-500">Altas no Mês</div>
                <div class="text-2xl font-semibold">{{ $altasMes }}</div>
            </div>
            <div class="rounded-xl border p-4 dark:border-gray-700">
                <div class="text-sm text-gray-500">Desistências</div>
                <div class="text-2xl font-semibold">{{ $desistencias }}</div>
            </div>
            <div class="rounded-xl border p-4 dark:border-gray-700">
                <div class="text-sm text-gray-500">Consultas Realizadas</div>
                <div class="text-2xl font-semibold">{{ $consultasRealizadas }}</div>
            </div>
            <div class="rounded-xl border p-4 dark:border-gray-700">
                <div class="text-sm text-gray-500">Usuários Ativos</div>
                <div class="text-2xl font-semibold">{{ $usuariosAtivos }}</div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
