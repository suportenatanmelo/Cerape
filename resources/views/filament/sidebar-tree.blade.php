@php
    $nodes = app(\App\Navigation\CerapeNavigationBuilder::class)->build();
@endphp

<div class="space-y-2 cerape-sidebar-tree">
    @foreach ($nodes as $node)
        <div
            class="rounded-2xl border border-gray-200/70 bg-white/80 shadow-sm backdrop-blur dark:border-gray-800 dark:bg-gray-900/70"
            x-data="{ expanded: false }"
            x-init="expanded = (localStorage.getItem('cerape-sidebar-open-groups:{{ $node['key'] }}') === '1') || {{ $node['isActive'] ? 'true' : 'false' }}"
        >
            <button
                type="button"
                class="flex w-full items-center justify-between rounded-2xl px-3 py-3 text-left text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-800"
                x-on:click="expanded = !expanded; localStorage.setItem('cerape-sidebar-open-groups:{{ $node['key'] }}', expanded ? '1' : '0')"
            >
                <span class="flex items-center gap-2.5">
                    <x-filament::icon :icon="$node['icon']" class="h-4.5 w-4.5" />
                    <span>{{ $node['label'] }}</span>
                </span>

                <x-filament::icon
                    icon="heroicon-o-chevron-right"
                    class="h-4 w-4 transition-transform duration-200"
                    x-bind:class="expanded ? 'rotate-90' : ''"
                />
            </button>

            <div x-show="expanded" x-collapse class="px-2 pb-2">
                <x-navigation.tree-menu :items="$node['items']" />
            </div>
        </div>
    @endforeach
</div>
