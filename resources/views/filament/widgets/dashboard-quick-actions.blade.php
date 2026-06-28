<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Ações Rápidas</x-slot>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($items as $item)
                <x-filament::button tag="a" :href="$item['url']" color="gray" class="h-24 justify-start gap-3 text-left">
                    <x-filament::icon :icon="$item['icon']" class="h-6 w-6" />
                    <span>{{ $item['label'] }}</span>
                </x-filament::button>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
