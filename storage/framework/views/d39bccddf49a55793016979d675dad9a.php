<?php
    $isFamily = \App\Support\PortalContext::isFamilyUser();
    $conversations = $this->getConversations();
    $messages = $this->getMessages();
    $acolhido = $this->getCurrentAcolhido();
?>

<?php if (isset($component)) { $__componentOriginal166a02a7c5ef5a9331faf66fa665c256 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.page.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div wire:poll.10s="refreshMessages" class="grid gap-6 xl:grid-cols-[320px_minmax(0,1fr)]">
        <?php if (isset($component)) { $__componentOriginalee08b1367eba38734199cf7829b1d1e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee08b1367eba38734199cf7829b1d1e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => ['class' => 'overflow-hidden']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'overflow-hidden']); ?>
            <div class="space-y-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-gray-500">
                        <?php echo e($isFamily ? 'Canal da familia' : 'Central da instituicao'); ?>

                    </p>
                    <h2 class="mt-2 text-xl font-semibold text-gray-950 dark:text-white">
                        Conversas de feedback
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-300">
                        <?php echo e($isFamily ? 'Envie mensagens para a equipe da CERAPE e acompanhe todo o historico do seu acolhido.' : 'Visualize feedbacks por acolhido, responda a familia e mantenha um historico organizado da conversa.'); ?>

                    </p>
                </div>

                <div class="space-y-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <button
                            type="button"
                            wire:click="selectAcolhido(<?php echo e($conversation['id']); ?>)"
                            class="w-full rounded-[1.6rem] border px-4 py-4 text-left transition <?php echo e($selectedAcolhidoId === $conversation['id'] ? 'border-gray-950 bg-gray-950 text-white shadow-lg' : 'border-gray-200 bg-white hover:border-gray-400 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:border-gray-600 dark:hover:bg-gray-800'); ?>"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-sm font-semibold">
                                        <?php echo e($conversation['nome']); ?>

                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($conversation['familia']) && ! $conversation['restrito']): ?>
                                        <div class="mt-1 text-xs uppercase tracking-[0.2em] <?php echo e($selectedAcolhidoId === $conversation['id'] ? 'text-white/70' : 'text-gray-500'); ?>">
                                            Familia: <?php echo e($conversation['familia']); ?>

                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($conversation['nao_lidas'] > 0): ?>
                                    <span class="rounded-full <?php echo e($selectedAcolhidoId === $conversation['id'] ? 'bg-white text-gray-950' : 'bg-amber-100 text-amber-900'); ?> px-2.5 py-1 text-[11px] font-semibold">
                                        <?php echo e($conversation['nao_lidas']); ?>

                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="mt-3 text-sm <?php echo e($selectedAcolhidoId === $conversation['id'] ? 'text-white/80' : 'text-gray-600 dark:text-gray-300'); ?>">
                                <?php echo e($conversation['ultimo_feedback'] ?: 'Nenhuma mensagem ainda. Inicie o contato quando desejar.'); ?>

                            </div>

                            <div class="mt-3 text-xs uppercase tracking-[0.18em] <?php echo e($selectedAcolhidoId === $conversation['id'] ? 'text-white/60' : 'text-gray-400'); ?>">
                                <?php echo e(filled($conversation['ultima_data']) ? \Illuminate\Support\Carbon::parse($conversation['ultima_data'])->format('d/m/Y H:i') : 'Conversa disponivel'); ?>

                            </div>
                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="rounded-[1.6rem] border border-dashed border-gray-300 px-5 py-10 text-center text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
                            Nenhum acolhido com conversa disponivel ainda.
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $attributes = $__attributesOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $component = $__componentOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__componentOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginalee08b1367eba38734199cf7829b1d1e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee08b1367eba38734199cf7829b1d1e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => ['class' => 'overflow-hidden']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'overflow-hidden']); ?>
            <div class="flex flex-col gap-6">
                <div class="rounded-[2rem] border border-gray-200 bg-gradient-to-r from-white via-gray-50 to-white px-6 py-5 shadow-sm dark:border-gray-800 dark:from-gray-900 dark:via-gray-950 dark:to-gray-900">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-gray-500 dark:text-gray-400">
                                Feedback familiar
                            </p>
                            <h2 class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">
                                <?php echo e($acolhido?->nome_completo_paciente ?? 'Selecione uma conversa'); ?>

                            </h2>
                            <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-300">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($acolhido): ?>
                                    <?php echo e($isFamily ? 'Converse com a equipe institucional e acompanhe as respostas no mesmo historico.' : 'Canal seguro entre equipe da CERAPE e familia vinculada ao acolhido.'); ?>

                                <?php else: ?>
                                    Escolha um acolhido ao lado para visualizar o historico e enviar uma nova mensagem.
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($acolhido && $acolhido->familyUsers->isNotEmpty() && ! $isFamily): ?>
                            <div class="rounded-full border border-gray-900 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-gray-700 dark:border-gray-200 dark:text-gray-200">
                                Familia: <?php echo e($acolhido->familyUsers->pluck('name')->join(', ')); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-gray-200 bg-[#fcfbf8] p-4 shadow-sm dark:border-gray-800 dark:bg-gray-950">
                    <div class="max-h-[34rem] space-y-4 overflow-y-auto px-1 py-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $isOwn = $this->isMessageFromCurrentUser($message);
                                $isFamilySender = $this->isFamilySender($message);
                            ?>

                            <div class="flex <?php echo e($isOwn ? 'justify-end' : 'justify-start'); ?>">
                                <article class="max-w-2xl rounded-[1.8rem] border px-5 py-4 shadow-sm <?php echo e($isOwn ? 'border-gray-950 bg-gray-950 text-white' : ($isFamilySender ? 'border-rose-200 bg-rose-50 text-rose-950 dark:border-rose-900/40 dark:bg-rose-950/30 dark:text-rose-100' : 'border-teal-200 bg-teal-50 text-teal-950 dark:border-teal-900/40 dark:bg-teal-950/30 dark:text-teal-100')); ?>">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="text-sm font-semibold">
                                            <?php echo e($message->sender?->name ?? 'Usuario'); ?>

                                        </div>
                                        <div class="text-[11px] uppercase tracking-[0.22em] <?php echo e($isOwn ? 'text-white/70' : 'text-current/60'); ?>">
                                            <?php echo e($isFamilySender ? 'Familia' : 'CERAPE'); ?>

                                        </div>
                                    </div>

                                    <div class="mt-3 whitespace-pre-line text-sm leading-6">
                                        <?php echo e($message->mensagem); ?>

                                    </div>

                                    <div class="mt-4 flex items-center justify-between gap-3 text-[11px] uppercase tracking-[0.2em] <?php echo e($isOwn ? 'text-white/70' : 'text-current/60'); ?>">
                                        <span><?php echo e($message->created_at?->format('d/m/Y H:i')); ?></span>
                                        <span><?php echo e($message->delivered_at ? 'Entregue' : 'Pendente'); ?></span>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="rounded-[1.8rem] border border-dashed border-gray-300 px-6 py-12 text-center text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                Nenhuma mensagem enviada ainda. Use o formulario abaixo para iniciar este canal de feedback.
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-xs uppercase tracking-[0.18em] text-gray-400">
                                Apenas usuarios autenticados podem participar desta conversa.
                            </p>

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-full bg-gray-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50"
                                <?php if($selectedAcolhidoId === null): echo 'disabled'; endif; ?>
                            >
                                Enviar feedback
                            </button>
                        </div>
                    </div>
                </form>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $attributes = $__attributesOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $component = $__componentOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__componentOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $attributes = $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $component = $__componentOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\cerape\resources\views\filament\pages\feedback-familiar.blade.php ENDPATH**/ ?>