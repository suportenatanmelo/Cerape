<?php
    $record = $getRecord();
    $rows = \App\Filament\Resources\GeradorAtividades\GeradorAtividadeResource::plannedActivities($record);
    $acolhidos = \App\Filament\Resources\GeradorAtividades\GeradorAtividadeResource::acolhidoNames($record?->acolhidos_ids);
    $periodo = \App\Filament\Resources\GeradorAtividades\GeradorAtividadeResource::getPeriodLabel($record);
?>

<div class="space-y-4">
    <div class="grid gap-4 md:grid-cols-1">
        <div class="rounded-2xl border border-primary-100 bg-gradient-to-br from-primary-50 to-white p-4 shadow-sm">
            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">Periodo</div>
            <div class="mt-2 text-base font-semibold text-gray-900"><?php echo e($periodo); ?></div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-gray-600">Ordem</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-gray-600">Atividades praticas</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-gray-600">Demanda</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-gray-600">Acolhidos</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr class="align-top">
                        <td class="whitespace-nowrap px-4 py-4 font-semibold text-gray-700"><?php echo e($row['ordem']); ?></td>
                        <td class="px-4 py-4 font-medium text-gray-900"><?php echo e($row['atividade_pratica'] ?: '-'); ?></td>
                        <td class="px-4 py-4 text-gray-700">
                            <div class="prose prose-sm max-w-none prose-p:my-1 prose-li:my-0.5">
                                <?php echo $row['demanda_html'] ?: '<span class="text-gray-400">-</span>'; ?>

                            </div>
                        </td>
                        <td class="px-4 py-4 text-gray-700">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($row['acolhidos_nomes'] === []): ?>
                                <span class="text-gray-400">-</span>
                            <?php else: ?>
                                <div class="flex flex-wrap gap-2">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $row['acolhidos_nomes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nome): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <span class="rounded-full bg-primary-50 px-3 py-1 text-xs font-medium text-primary-700 ring-1 ring-primary-200">
                                            <?php echo e($nome); ?>

                                        </span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                    </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">
                            Nenhuma atividade cadastrada para este quadro semanal.
                        </td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\cerape\resources\views/filament/resources/gerador-atividades/weekly-activities-table.blade.php ENDPATH**/ ?>