<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Relatorio geral do acolhido</title>
    <style>
        * { box-sizing: border-box; }
        body { color: #111827; font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.45; margin: 0; }
        .page { padding: 28px; }
        .header { border-bottom: 2px solid #d97706; display: table; padding-bottom: 18px; width: 100%; }
        .avatar-wrap { display: table-cell; vertical-align: top; width: 96px; }
        .avatar { border: 2px solid #e5e7eb; border-radius: 50%; height: 82px; object-fit: cover; width: 82px; }
        .avatar-empty { background: #f3f4f6; border: 2px solid #e5e7eb; border-radius: 50%; color: #6b7280; height: 82px; padding-top: 29px; text-align: center; width: 82px; }
        .title-wrap { display: table-cell; vertical-align: top; }
        h1 { font-size: 22px; margin: 0 0 6px; }
        h2 { color: #92400e; font-size: 15px; margin: 22px 0 10px; }
        .muted { color: #6b7280; }
        .status { border-radius: 999px; display: inline-block; font-weight: bold; margin-top: 8px; padding: 4px 10px; }
        .status-active { background: #dcfce7; color: #166534; }
        .status-inactive { background: #fee2e2; color: #991b1b; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #f9fafb; color: #4b5563; font-size: 10px; text-align: left; text-transform: uppercase; width: 34%; }
        th, td { border: 1px solid #e5e7eb; padding: 7px; vertical-align: top; }
        .section { page-break-inside: avoid; }
        .footer { border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 10px; margin-top: 26px; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="avatar-wrap">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fotoAcolhido): ?>
                    <img src="<?php echo e($fotoAcolhido); ?>" class="avatar" alt="">
                <?php else: ?>
                    <div class="avatar-empty">Sem foto</div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="title-wrap">
                <h1>Relatorio geral do acolhido</h1>
                <div><strong><?php echo e($acolhido->nome_completo_paciente); ?></strong></div>
                <div class="muted">Responsavel: <?php echo e($acolhido->user?->name ?? '-'); ?></div>
                <div class="muted">Emitido em: <?php echo e(now()->format('d/m/Y H:i')); ?></div>
                <span class="status <?php echo e($acolhido->ativo ? 'status-active' : 'status-inactive'); ?>">
                    <?php echo e($acolhido->ativo ? 'Ativo' : 'Desativado'); ?>

                </span>
            </div>
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

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($acolhido->avaliacoesPessoais->isNotEmpty()): ?>
            <div class="section">
                <h2>Avaliacoes pessoais registradas</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Usuario</th>
                            <th>Tempo de casa</th>
                            <th>Media</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $acolhido->avaliacoesPessoais->sortByDesc('created_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $avaliacao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($avaliacao->created_at?->format('d/m/Y H:i')); ?></td>
                                <td><?php echo e($avaliacao->user?->name ?? '-'); ?></td>
                                <td><?php echo e($avaliacao->dias_na_casa ?? '-'); ?></td>
                                <td><?php echo e(number_format((float) $avaliacao->Total, 2, ',', '.')); ?> / 3</td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="footer">
            Relatorio gerado automaticamente pelo sistema Cerape.
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\cerape\resources\views/pdf/acolhido-report.blade.php ENDPATH**/ ?>