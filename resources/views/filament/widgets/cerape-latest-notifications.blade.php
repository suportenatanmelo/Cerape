<x-filament::section>
    <div class="flex items-start justify-between gap-4">
        <div>
            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-100">
                Últimas notificações/avisos
            </h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Atualiza automaticamente.
            </p>
        </div>
    </div>

    <div class="mt-4">
        @if (count($notifications) === 0)
            <div class="p-4 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                Nenhuma notificação.
            </div>
        @else
            <ul class="space-y-3">
                @foreach ($notifications as $notification)
                    <li class="p-4 bg-white border border-gray-200 rounded-lg dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex items-start justify-between gap-3">
                            <p class="text-sm text-gray-800 dark:text-gray-200">
                                {{ $notification['message'] }}
                            </p>
                            @if (!empty($notification['created_at']))
                                <span class="text-xs text-gray-500 whitespace-nowrap dark:text-gray-400">
                                    {{ $notification['created_at'] }}
                                </span>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-filament::section>

