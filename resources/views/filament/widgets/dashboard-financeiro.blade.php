<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Indicadores Gerais (Financeiro)</x-slot>
        <div class="space-y-3">
            <div class="flex justify-between rounded-xl border p-4 dark:border-gray-700">
                <span>Receitas</span><strong>{{ $receitas }}</strong>
            </div>
            <div class="flex justify-between rounded-xl border p-4 dark:border-gray-700">
                <span>Despesas</span><strong>{{ $despesas }}</strong>
            </div>
            <div class="flex justify-between rounded-xl border p-4 dark:border-gray-700">
                <span>Saldo</span><strong>{{ $saldo }}</strong>
            </div>
            <div class="flex justify-between rounded-xl border p-4 dark:border-gray-700">
                <span>Contas a pagar</span><strong>{{ $contasPagar }}</strong>
            </div>
            <div class="flex justify-between rounded-xl border p-4 dark:border-gray-700">
                <span>Contas a receber</span><strong>{{ $contasReceber }}</strong>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
