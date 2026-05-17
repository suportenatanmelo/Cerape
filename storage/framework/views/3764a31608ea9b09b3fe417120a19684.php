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
    <div class="grid gap-6 xl:grid-cols-[360px_minmax(0,1fr)]">
        <div class="space-y-6">
            <?php if (isset($component)) { $__componentOriginalee08b1367eba38734199cf7829b1d1e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee08b1367eba38734199cf7829b1d1e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <div class="space-y-5">
                    <div class="flex flex-col items-center rounded-2xl border border-gray-200 bg-gradient-to-b from-amber-50 to-white p-6 text-center shadow-sm dark:border-gray-800 dark:from-gray-900 dark:to-gray-950">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fotoAcolhido): ?>
                            <img src="<?php echo e($fotoAcolhido); ?>" alt="" class="h-44 w-32 rounded-2xl border-4 border-white object-cover shadow-md">
                        <?php else: ?>
                            <div class="flex h-44 w-32 items-center justify-center rounded-2xl border-4 border-white bg-gray-100 text-3xl font-semibold text-gray-500 shadow-md dark:bg-gray-800 dark:text-gray-300">
                                <?php echo e(str($acolhido?->nome_completo_paciente ?? '?')->substr(0, 1)->upper()); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="mt-4 text-lg font-semibold text-gray-950 dark:text-white">
                            <?php echo e($acolhido?->nome_completo_paciente ?? 'Acolhido nao informado'); ?>

                        </div>

                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            <?php echo e($record->dias_na_casa ?? '-'); ?>

                        </div>

                        <div class="mt-4 inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-500/10 dark:text-amber-300">
                            Relatorio profissional de evolucao pessoal
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Media geral consolidada</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-950 dark:text-white"><?php echo e($formatScore((float) $mediaDeTodos)); ?></div>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Soma das medias individuais limitada a 3</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-950 dark:text-white"><?php echo e(number_format((float) $somaMediasIndividuais, 2, ',', '.')); ?></div>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Usuarios avaliadores</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-950 dark:text-white"><?php echo e($totalAvaliadores); ?></div>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Avaliacoes registradas</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-950 dark:text-white"><?php echo e($totalAvaliacoes); ?></div>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $personalData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                                <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400"><?php echo e($item['label']); ?></div>
                                <div class="mt-2 text-sm text-gray-800 dark:text-gray-100"><?php echo e($item['value']); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                        <div class="mb-4">
                            <div class="text-base font-semibold text-gray-950 dark:text-white">Media geral por criterio</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Consolidado de todos os votos por categoria avaliada.</div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $criteriaAverages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-950">
                                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e($label); ?></div>
                                    <div class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white"><?php echo e($formatScore((float) $value)); ?></div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
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
        </div>

        <div class="space-y-6">
            <?php if (isset($component)) { $__componentOriginalee08b1367eba38734199cf7829b1d1e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee08b1367eba38734199cf7829b1d1e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => ['heading' => 'Comparativos de periodo','description' => 'Acompanhe como a media atual se comporta em relacao ao periodo imediatamente anterior.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['heading' => 'Comparativos de periodo','description' => 'Acompanhe como a media atual se comporta em relacao ao periodo imediatamente anterior.']); ?>
                <div class="grid gap-4 lg:grid-cols-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['semanal', 'mensal', 'semestral']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comparisonKey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php ($comparison = $periodComparisons[$comparisonKey]); ?>
                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                            <div class="text-base font-semibold text-gray-950 dark:text-white"><?php echo e($comparison['label']); ?></div>
                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400"><?php echo e($comparison['current_label']); ?> vs <?php echo e($comparison['previous_label']); ?></div>

                            <div class="mt-4 space-y-4">
                                <div class="rounded-xl bg-amber-50 p-4 dark:bg-amber-500/10">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-amber-700 dark:text-amber-300">Media das avaliacoes</div>
                                    <div class="mt-2 flex items-end justify-between gap-3">
                                        <div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Atual</div>
                                            <div class="text-xl font-semibold text-gray-950 dark:text-white"><?php echo e($formatScore((float) $comparison['raw_current'])); ?></div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Anterior</div>
                                            <div class="text-sm font-medium text-gray-700 dark:text-gray-200"><?php echo e($formatScore((float) $comparison['raw_previous'])); ?></div>
                                            <div class="mt-1 text-xs font-semibold <?php echo e($comparison['raw_delta'] >= 0 ? 'text-emerald-600' : 'text-rose-600'); ?>">
                                                <?php echo e($comparison['raw_delta'] >= 0 ? '+' : ''); ?><?php echo e(number_format((float) $comparison['raw_delta'], 2, ',', '.')); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-xl bg-teal-50 p-4 dark:bg-teal-500/10">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-teal-700 dark:text-teal-300">Media consolidada dos avaliadores</div>
                                    <div class="mt-2 flex items-end justify-between gap-3">
                                        <div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Atual</div>
                                            <div class="text-xl font-semibold text-gray-950 dark:text-white"><?php echo e($formatScore((float) $comparison['consolidated_current'])); ?></div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Anterior</div>
                                            <div class="text-sm font-medium text-gray-700 dark:text-gray-200"><?php echo e($formatScore((float) $comparison['consolidated_previous'])); ?></div>
                                            <div class="mt-1 text-xs font-semibold <?php echo e($comparison['consolidated_delta'] >= 0 ? 'text-emerald-600' : 'text-rose-600'); ?>">
                                                <?php echo e($comparison['consolidated_delta'] >= 0 ? '+' : ''); ?><?php echo e(number_format((float) $comparison['consolidated_delta'], 2, ',', '.')); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div class="rounded-xl border border-gray-200 p-3 dark:border-gray-800">
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Avaliacoes no periodo atual</div>
                                        <div class="mt-1 text-lg font-semibold text-gray-950 dark:text-white"><?php echo e($comparison['current_total_evaluations']); ?></div>
                                    </div>
                                    <div class="rounded-xl border border-gray-200 p-3 dark:border-gray-800">
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Avaliacoes no periodo anterior</div>
                                        <div class="mt-1 text-lg font-semibold text-gray-950 dark:text-white"><?php echo e($comparison['previous_total_evaluations']); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => ['heading' => 'Resumo detalhado por avaliador','description' => 'Media individual, categorias votadas e historico resumido de cada profissional.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['heading' => 'Resumo detalhado por avaliador','description' => 'Media individual, categorias votadas e historico resumido de cada profissional.']); ?>
                <div class="space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                            <div class="flex flex-col gap-4 border-b border-gray-200 bg-gray-50 p-5 dark:border-gray-800 dark:bg-gray-950/60 lg:flex-row lg:items-center lg:justify-between">
                                <div class="flex items-center gap-4">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['foto']): ?>
                                        <img src="<?php echo e($item['foto']); ?>" alt="" class="h-14 w-14 rounded-full object-cover shadow-sm">
                                    <?php else: ?>
                                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-lg font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                            <?php echo e(str($item['user']?->name ?? '?')->substr(0, 1)->upper()); ?>

                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <div>
                                        <div class="text-lg font-semibold text-gray-950 dark:text-white"><?php echo e($item['user']?->name ?? 'Usuario nao informado'); ?></div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($item['user']?->email ?? 'Sem e-mail cadastrado'); ?></div>
                                    </div>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-3">
                                    <div class="rounded-xl bg-white px-4 py-3 text-center shadow-sm dark:bg-gray-900">
                                        <div class="text-xs uppercase tracking-wide text-gray-500">Qtd. votos</div>
                                        <div class="mt-1 text-xl font-semibold text-gray-950 dark:text-white"><?php echo e($item['quantidade']); ?></div>
                                    </div>
                                    <div class="rounded-xl bg-white px-4 py-3 text-center shadow-sm dark:bg-gray-900">
                                        <div class="text-xs uppercase tracking-wide text-gray-500">Media individual</div>
                                        <div class="mt-1 text-xl font-semibold text-gray-950 dark:text-white"><?php echo e($formatScore((float) $item['media'])); ?></div>
                                    </div>
                                    <div class="rounded-xl bg-white px-4 py-3 text-center shadow-sm dark:bg-gray-900">
                                        <div class="text-xs uppercase tracking-wide text-gray-500">Ultimo voto</div>
                                        <div class="mt-1 text-sm font-semibold text-gray-950 dark:text-white"><?php echo e($item['ultima_avaliacao']?->created_at?->format('d/m/Y') ?? '-'); ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4 p-5">
                                <div class="grid gap-4 lg:grid-cols-5">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $item['criterios']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-950">
                                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400"><?php echo e($label); ?></div>
                                            <div class="mt-2 text-xl font-semibold text-gray-950 dark:text-white"><?php echo e($formatScore((float) $value)); ?></div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-800">
                                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-950 dark:text-gray-400">
                                            <tr>
                                                <th class="px-4 py-3">Data</th>
                                                <th class="px-4 py-3">Controle</th>
                                                <th class="px-4 py-3">Autonomia</th>
                                                <th class="px-4 py-3">Transparencia</th>
                                                <th class="px-4 py-3">Superacao</th>
                                                <th class="px-4 py-3">Autocuidado</th>
                                                <th class="px-4 py-3">Media final</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $item['avaliacoes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $avaliacao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="text-gray-700 dark:text-gray-200">
                                                    <td class="px-4 py-3 whitespace-nowrap"><?php echo e($avaliacao->created_at?->format('d/m/Y')); ?></td>
                                                    <td class="px-4 py-3"><?php echo e($formatScore((float) $avaliacao->controler)); ?></td>
                                                    <td class="px-4 py-3"><?php echo e($formatScore((float) $avaliacao->autonomia)); ?></td>
                                                    <td class="px-4 py-3"><?php echo e($formatScore((float) $avaliacao->transparencia)); ?></td>
                                                    <td class="px-4 py-3"><?php echo e($formatScore((float) $avaliacao->superacao)); ?></td>
                                                    <td class="px-4 py-3"><?php echo e($formatScore((float) $avaliacao->autocuidado)); ?></td>
                                                    <td class="px-4 py-3 font-semibold"><?php echo e($formatScore((float) $avaliacao->Total)); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
                            Nenhum avaliador com votos registrados para este acolhido.
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => ['heading' => 'Historico cronologico das avaliacoes','description' => 'Cada registro individual com os valores atribuidos por categoria.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['heading' => 'Historico cronologico das avaliacoes','description' => 'Cada registro individual com os valores atribuidos por categoria.']); ?>
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-800">
                            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-950 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-3">Data</th>
                                    <th class="px-4 py-3">Avaliador</th>
                                    <th class="px-4 py-3">Controle</th>
                                    <th class="px-4 py-3">Autonomia</th>
                                    <th class="px-4 py-3">Transparencia</th>
                                    <th class="px-4 py-3">Superacao</th>
                                    <th class="px-4 py-3">Autocuidado</th>
                                    <th class="px-4 py-3">Media final</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $avaliacoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $avaliacao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="text-gray-700 dark:text-gray-200">
                                        <td class="px-4 py-3 whitespace-nowrap"><?php echo e($avaliacao->created_at?->format('d/m/Y')); ?></td>
                                        <td class="px-4 py-3"><?php echo e($avaliacao->user?->name ?? '-'); ?></td>
                                        <td class="px-4 py-3"><?php echo e($formatScore((float) $avaliacao->controler)); ?></td>
                                        <td class="px-4 py-3"><?php echo e($formatScore((float) $avaliacao->autonomia)); ?></td>
                                        <td class="px-4 py-3"><?php echo e($formatScore((float) $avaliacao->transparencia)); ?></td>
                                        <td class="px-4 py-3"><?php echo e($formatScore((float) $avaliacao->superacao)); ?></td>
                                        <td class="px-4 py-3"><?php echo e($formatScore((float) $avaliacao->autocuidado)); ?></td>
                                        <td class="px-4 py-3 font-semibold"><?php echo e($formatScore((float) $avaliacao->Total)); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                            Nenhuma avaliacao encontrada para este acolhido.
                                        </td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => ['heading' => 'Logica de calculo das medias','description' => 'Explicacao objetiva de como o sistema consolida as notas apresentadas neste relatorio.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['heading' => 'Logica de calculo das medias','description' => 'Explicacao objetiva de como o sistema consolida as notas apresentadas neste relatorio.']); ?>
                <div class="grid gap-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $logicasMedias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logica): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                            <div class="text-base font-semibold text-gray-950 dark:text-white"><?php echo e($logica['titulo']); ?></div>
                            <div class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-300"><?php echo e($logica['descricao']); ?></div>
                            <div class="mt-3 rounded-xl bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-800 dark:bg-amber-500/10 dark:text-amber-300"><?php echo e($logica['formula']); ?></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
<?php /**PATH C:\laragon\www\cerape\resources\views\filament\resources\avaliacao-pessoals\pages\relatorio-avaliacao-pessoal.blade.php ENDPATH**/ ?>