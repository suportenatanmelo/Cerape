<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Relatorio de substancia psicoativa</title>
    <style>
        * { box-sizing: border-box; }
        body { color: #111827; font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.45; margin: 0; }
        .page { padding: 28px; }
        .brand-bar { border-bottom: 1px solid #e5e7eb; margin-bottom: 16px; padding-bottom: 12px; width: 100%; }
        .brand-logo { display: block; height: auto; max-height: 62px; max-width: 220px; }
        .header { border-bottom: 2px solid #d97706; padding-bottom: 18px; width: 100%; }
        h1 { font-size: 22px; margin: 0 0 6px; }
        h2 { color: #92400e; font-size: 15px; margin: 22px 0 10px; }
        .muted { color: #6b7280; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #f9fafb; color: #4b5563; font-size: 10px; text-align: left; text-transform: uppercase; width: 34%; }
        th, td { border: 1px solid #e5e7eb; padding: 7px; vertical-align: top; }
        .section { page-break-inside: avoid; }
        .footer { border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 10px; margin-top: 26px; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="page">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logoCerape): ?>
            <div class="brand-bar">
                <img src="<?php echo e($logoCerape); ?>" class="brand-logo" alt="Logo Cerape">
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="header">
            <h1>Relatorio de substancia psicoativa</h1>
            <div><strong><?php echo e($record->acolhido?->nome_completo_paciente ?? 'Acolhido nao informado'); ?></strong></div>
            <div class="muted">Substancias: <?php echo e($formatValue($record->nome)); ?></div>
            <div class="muted">Emitido em: <?php echo e(now()->format('d/m/Y H:i')); ?></div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $title => $fields): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="section">
                <h2><?php echo e($title); ?></h2>
                <table>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th><?php echo e($label); ?></th>
                                <td><?php echo e($formatValue($value)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="footer">
            Relatorio gerado automaticamente pelo sistema Cerape.
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\cerape\resources\views/pdf/substancia-psicoativa-report.blade.php ENDPATH**/ ?>