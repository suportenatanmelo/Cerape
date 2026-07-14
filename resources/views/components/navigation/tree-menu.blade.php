@props([
    'items' => [],
    'level' => 0,
])

@php
    $indent = $level * 0.75; // rem
@endphp

<ul class="flex flex-col gap-1" style="padding-left: calc({{ $indent }}rem);">
    @foreach ($items as $item)
        @php
            $hasChildren = !empty($item['children'] ?? []);
            $isExpanded = (bool) ($item['isExpanded'] ?? false);
            $isActive = (bool) ($item['isActive'] ?? false);
            $badge = $item['badge'] ?? null;
            $badgeColor = $item['badgeColor'] ?? 'gray';
        @endphp

        <li
            x-data="{ expanded: false }"
            x-init="expanded = (localStorage.getItem('cerape-sidebar-open-item:{{ $item['key'] }}') === '1') || {{ $isExpanded ? 'true' : 'false' }}; $watch('expanded', val => localStorage.setItem('cerape-sidebar-open-item:{{ $item['key'] }}', val ? '1' : '0'))"
            x-bind:aria-expanded="expanded.toString()"
            id="cerape-item-{{ $item['key'] }}"
            class="rounded-xl border border-transparent transition-all duration-200"
        >
            @if ($hasChildren)
                <button
                    type="button"
                    class="flex w-full items-center justify-between rounded-xl px-3 py-2.5 text-left text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-800/80"
                    @click="expanded = !expanded"
                    :aria-controls="'cerape-children-{{ $item['key'] }}'"
                >
                    <span class="flex items-center gap-2">
                        @if (!empty($item['icon']))
                            <x-filament::icon :icon="$item['icon']" class="h-4 w-4" />
                        @endif
                        <span class="truncate">{{ $item['label'] }}</span>
                    </span>

                    <span class="transition-transform duration-200" x-bind:class="expanded ? 'rotate-90' : ''">
                        <x-filament::icon icon="heroicon-o-chevron-right" class="h-4 w-4" />
                    </span>
                </button>
            @else
                <a
                    href="{{ $item['url'] ?? '#' }}"
                    class="flex items-center justify-between rounded-xl px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-800/80 {{ $isActive ? 'bg-primary-50 text-primary-700 dark:bg-primary-500/10 dark:text-primary-300' : '' }}"
                >
                    <span class="flex items-center gap-2">
                        @if (!empty($item['icon']))
                            <x-filament::icon :icon="$item['icon']" class="h-4 w-4" />
                        @endif
                        <span class="truncate">{{ $item['label'] }}</span>
                    </span>

                    @if (filled($badge))
                        <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-[11px] font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-200">
                            {{ $badge }}
                        </span>
                    @endif
                </a>
            @endif

            @if ($hasChildren)
                <div
                    id="cerape-children-{{ $item['key'] }}"
                    x-show="expanded"
                    x-collapse
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-1"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-1"
                    class="mt-1 space-y-1"
                >
                    <x-navigation.tree-menu :items="$item['children']" :level="$level + 1" />
                </div>
            @endif
        </li>
    @endforeach
</ul>
