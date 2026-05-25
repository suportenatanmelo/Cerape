<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Quadro semanal de atividades</title>
    <style>
        * { box-sizing: border-box; }
        body { color: #111827; font-family: DejaVu Sans, sans-serif; font-size: 10px; line-height: 1.35; margin: 0; }
        .page { padding: 20px; }
        .title { border: 1px solid #9ca3af; border-bottom: 0; font-size: 20px; font-weight: bold; padding: 10px 14px; text-align: center; text-transform: uppercase; }
        .title span { color: #111827; }
        .meta { border: 1px solid #9ca3af; border-bottom: 0; padding: 8px 12px; }
        .meta-line { margin-bottom: 3px; }
        .meta-line:last-child { margin-bottom: 0; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #9ca3af; padding: 6px 7px; vertical-align: top; }
        th { background: #f3f4f6; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .col-order { text-align: center; width: 7%; }
        .col-activity { width: 26%; }
        .col-demand { width: 47%; }
        .col-names { width: 20%; }
        .demand p { margin: 0 0 5px; }
        .demand ul, .demand ol { margin: 4px 0 0 16px; padding: 0; }
        .demand li { margin-bottom: 3px; }
        .names-list { margin: 0; padding-left: 16px; }
        .names-list li { margin-bottom: 2px; }
        .observacoes { border: 1px solid #9ca3af; border-top: 0; padding: 10px 12px; }
        .observacoes h2 { font-size: 11px; margin: 0 0 6px; text-transform: uppercase; }
        .footer { color: #6b7280; font-size: 9px; margin-top: 10px; text-align: right; }
    </style>
</head>
<body>
    <div class="page">
        <?php echo $__env->make('pdf.partials.cerape-brand-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="title">
            <?php echo e($record->titulo); ?> <span>- <?php echo e($periodoLabel); ?></span>
        </div>
        <div class="meta">
            <div class="meta-line"><strong>Responsavel:</strong> <?php echo e($record->user?->name ?? '-'); ?></div>
            <div class="meta-line"><strong>Emitido em:</strong> <?php echo e(now()->format('d/m/Y H:i')); ?></div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="col-order">Ordem</th>
                    <th class="col-activity">Atividades praticas</th>
                    <th class="col-demand">Demanda</th>
                    <th class="col-names">Nome</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $atividadesPlanejadas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $atividade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr>
                        <td class="col-order"><?php echo e($atividade['ordem']); ?></td>
                        <td class="col-activity"><?php echo e($atividade['atividade_pratica'] ?: '-'); ?></td>
                        <td class="col-demand demand"><?php echo $atividade['demanda_html'] ?: '-'; ?></td>
                        <td class="col-names">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($atividade['acolhidos_nomes'] === []): ?>
                                -
                            <?php else: ?>
                                <ul class="names-list">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $atividade['acolhidos_nomes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nome): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <li><?php echo e($nome); ?></li>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </ul>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                    </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr>
                        <td colspan="4">Nenhuma atividade cadastrada para este periodo.</td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>

        <div class="observacoes">
            <h2>Observacoes complementares</h2>
            <?php echo filled($record->observacoes) ? nl2br(e($record->observacoes)) : 'Sem observacoes adicionais.'; ?>

        </div>

        <div class="footer">
            Relatorio gerado automaticamente pelo sistema Cerape.
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\cerape\resources\views/pdf/gerador-atividade-report.blade.php ENDPATH**/ ?>