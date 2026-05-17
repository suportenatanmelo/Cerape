@php
    $isFamily = \App\Support\PortalContext::isFamilyUser();
    $conversations = $this->getConversations();
    $messages = $this->getMessages();
    $acolhido = $this->getCurrentAcolhido();
@endphp

<x-filament-panels::page>
    <div wire:poll.10s="refreshMessages" class="grid gap-6 xl:grid-cols-[320px_minmax(0,1fr)]">
        <x-filament::section class="overflow-hidden">
            <div class="space-y-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-gray-500">
                        {{ $isFamily ? 'Canal da familia' : 'Central da instituicao' }}
                    </p>
                    <h2 class="mt-2 text-xl font-semibold text-gray-950 dark:text-white">
                        Conversas de feedback
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-300">
                        {{ $isFamily ? 'Envie mensagens para a equipe da CERAPE e acompanhe todo o historico do seu acolhido.' : 'Visualize feedbacks por acolhido, responda a familia e mantenha um historico organizado da conversa.' }}
                    </p>
                </div>

                <div class="space-y-3">
                    @forelse ($conversations as $conversation)
                        <button
                            type="button"
                            wire:click="selectAcolhido({{ $conversation['id'] }})"
                            class="w-full rounded-[1.6rem] border px-4 py-4 text-left transition {{ $selectedAcolhidoId === $conversation['id'] ? 'border-gray-950 bg-gray-950 text-white shadow-lg' : 'border-gray-200 bg-white hover:border-gray-400 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:border-gray-600 dark:hover:bg-gray-800' }}"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-sm font-semibold">
                                        {{ $conversation['nome'] }}
                                    </div>
                                    @if (filled($conversation['familia']) && ! $conversation['restrito'])
                                        <div class="mt-1 text-xs uppercase tracking-[0.2em] {{ $selectedAcolhidoId === $conversation['id'] ? 'text-white/70' : 'text-gray-500' }}">
                                            Familia: {{ $conversation['familia'] }}
                                        </div>
                                    @endif
                                </div>

                                @if ($conversation['nao_lidas'] > 0)
                                    <span class="rounded-full {{ $selectedAcolhidoId === $conversation['id'] ? 'bg-white text-gray-950' : 'bg-amber-100 text-amber-900' }} px-2.5 py-1 text-[11px] font-semibold">
                                        {{ $conversation['nao_lidas'] }}
                                    </span>
                                @endif
                            </div>

                            <div class="mt-3 text-sm {{ $selectedAcolhidoId === $conversation['id'] ? 'text-white/80' : 'text-gray-600 dark:text-gray-300' }}">
                                {{ $conversation['ultimo_feedback'] ?: 'Nenhuma mensagem ainda. Inicie o contato quando desejar.' }}
                            </div>

                            <div class="mt-3 text-xs uppercase tracking-[0.18em] {{ $selectedAcolhidoId === $conversation['id'] ? 'text-white/60' : 'text-gray-400' }}">
                                {{ filled($conversation['ultima_data']) ? \Illuminate\Support\Carbon::parse($conversation['ultima_data'])->format('d/m/Y H:i') : 'Conversa disponivel' }}
                            </div>
                        </button>
                    @empty
                        <div class="rounded-[1.6rem] border border-dashed border-gray-300 px-5 py-10 text-center text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
                            Nenhum acolhido com conversa disponivel ainda.
                        </div>
                    @endforelse
                </div>
            </div>
        </x-filament::section>

        <x-filament::section class="overflow-hidden">
            <div class="flex flex-col gap-6">
                <div class="rounded-[2rem] border border-gray-200 bg-gradient-to-r from-white via-gray-50 to-white px-6 py-5 shadow-sm dark:border-gray-800 dark:from-gray-900 dark:via-gray-950 dark:to-gray-900">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-gray-500 dark:text-gray-400">
                                Feedback familiar
                            </p>
                            <h2 class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">
                                {{ $acolhido?->nome_completo_paciente ?? 'Selecione uma conversa' }}
                            </h2>
                            <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-300">
                                @if ($acolhido)
                                    {{ $isFamily ? 'Converse com a equipe institucional e acompanhe as respostas no mesmo historico.' : 'Canal seguro entre equipe da CERAPE e familia vinculada ao acolhido.' }}
                                @else
                                    Escolha um acolhido ao lado para visualizar o historico e enviar uma nova mensagem.
                                @endif
                            </p>
                        </div>

                        @if ($acolhido && $acolhido->familyUsers->isNotEmpty() && ! $isFamily)
                            <div class="rounded-full border border-gray-900 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-gray-700 dark:border-gray-200 dark:text-gray-200">
                                Familia: {{ $acolhido->familyUsers->pluck('name')->join(', ') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="rounded-[2rem] border border-gray-200 bg-[#fcfbf8] p-4 shadow-sm dark:border-gray-800 dark:bg-gray-950">
                    <div class="max-h-[34rem] space-y-4 overflow-y-auto px-1 py-2">
                        @forelse ($messages as $message)
                            @php
                                $isOwn = $this->isMessageFromCurrentUser($message);
                                $isFamilySender = $this->isFamilySender($message);
                            @endphp

                            <div class="flex {{ $isOwn ? 'justify-end' : 'justify-start' }}">
                                <article class="max-w-2xl rounded-[1.8rem] border px-5 py-4 shadow-sm {{ $isOwn ? 'border-gray-950 bg-gray-950 text-white' : ($isFamilySender ? 'border-rose-200 bg-rose-50 text-rose-950 dark:border-rose-900/40 dark:bg-rose-950/30 dark:text-rose-100' : 'border-teal-200 bg-teal-50 text-teal-950 dark:border-teal-900/40 dark:bg-teal-950/30 dark:text-teal-100') }}">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="text-sm font-semibold">
                                            {{ $message->sender?->name ?? 'Usuario' }}
                                        </div>
                                        <div class="text-[11px] uppercase tracking-[0.22em] {{ $isOwn ? 'text-white/70' : 'text-current/60' }}">
                                            {{ $isFamilySender ? 'Familia' : 'CERAPE' }}
                                        </div>
                                    </div>

                                    <div class="mt-3 whitespace-pre-line text-sm leading-6">
                                        {{ $message->mensagem }}
                                    </div>

                                    <div class="mt-4 flex items-center justify-between gap-3 text-[11px] uppercase tracking-[0.2em] {{ $isOwn ? 'text-white/70' : 'text-current/60' }}">
                                        <span>{{ $message->created_at?->format('d/m/Y H:i') }}</span>
                                        <span>{{ $message->delivered_at ? 'Entregue' : 'Pendente' }}</span>
                                    </div>
                                </article>
                            </div>
                        @empty
                            <div class="rounded-[1.8rem] border border-dashed border-gray-300 px-6 py-12 text-center text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                Nenhuma mensagem enviada ainda. Use o formulario abaixo para iniciar este canal de feedback.
                            </div>
                        @endforelse
                    </div>
                </div>

                <form wire:submit="sendMessage" class="rounded-[2rem] border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="space-y-4">
                        <div>
                            <label for="feedback-message" class="text-sm font-semibold text-gray-950 dark:text-white">
                                Nova mensagem
                            </label>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Sua mensagem fica salva no historico e gera notificacao para a outra parte.
                            </p>
                        </div>

                        <div>
                            <textarea
                                id="feedback-message"
                                wire:model.live="message"
                                rows="5"
                                placeholder="Escreva aqui seu feedback, duvida ou atualizacao importante..."
                                class="w-full rounded-[1.4rem] border border-gray-300 bg-gray-50 px-4 py-4 text-sm text-gray-900 shadow-sm outline-none transition focus:border-gray-900 focus:bg-white focus:ring-0 dark:border-gray-700 dark:bg-gray-950 dark:text-white dark:focus:border-gray-300"
                            ></textarea>
                            @error('message')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-xs uppercase tracking-[0.18em] text-gray-400">
                                Apenas usuarios autenticados podem participar desta conversa.
                            </p>

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-full bg-gray-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50"
                                @disabled($selectedAcolhidoId === null)
                            >
                                Enviar feedback
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
