<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Relatorio de auto avaliacao</title>
    <style>
        * { box-sizing: border-box; }
        body { color: #111827; font-family: DejaVu Sans, sans-serif; font-size: 10px; line-height: 1.35; margin: 0; }
        .page { padding: 20px; }
        .brand-bar { border-bottom: 1px solid #e5e7eb; margin-bottom: 14px; padding-bottom: 10px; width: 100%; }
        .brand-logo { display: block; height: auto; max-height: 58px; max-width: 220px; }
        .header { border-bottom: 2px solid #0f766e; padding-bottom: 12px; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        .muted { color: #6b7280; }
        .highlight { background: #ccfbf1; border-radius: 999px; color: #115e59; display: inline-block; font-size: 9px; font-weight: bold; margin-top: 8px; padding: 4px 10px; }
        table { border-collapse: collapse; margin-top: 16px; width: 100%; }
        th { background: #f9fafb; color: #4b5563; font-size: 8px; text-align: left; text-transform: uppercase; }
        th, td { border: 1px solid #d1d5db; padding: 8px 6px; vertical-align: middle; }
        .blank-cell { height: 28px; }
        .footer { border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 9px; margin-top: 16px; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="page">
        <?php echo $__env->make('pdf.partials.cerape-brand-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="header">
            <h1>Auto Avaliacao dos acolhidos</h1>
            <div class="muted">Relatorio em formato de apoio para preenchimento manual da equipe.</div>
            <div class="muted">Gerado em: <?php echo e($geradoEm->format('d/m/Y H:i')); ?></div>
            <div class="muted">Total de acolhidos listados: <?php echo e($acolhidos->count()); ?></div>
            <div class="highlight">Categorias em branco para preenchimento manual</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">Matricula</th>
                    <th style="width: 30%;">Nome do acolhido</th>
                    <th style="width: 10%;">Dias na casa</th>
                    <th style="width: 13%;">Controle</th>
                    <th style="width: 13%;">Autonomia</th>
                    <th style="width: 13%;">Transparencia</th>
                    <th style="width: 13%;">Superacao</th>
                    <th style="width: 13%;">AutoCuidado</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $acolhidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acolhido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($acolhido['matricula']); ?></td>
                        <td><?php echo e($acolhido['nome']); ?></td>
                        <td><?php echo e($acolhido['dias_na_casa']); ?></td>
                        <td class="blank-cell"></td>
                        <td class="blank-cell"></td>
                        <td class="blank-cell"></td>
                        <td class="blank-cell"></td>
                        <td class="blank-cell"></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8">Nenhum acolhido cadastrado no sistema.</td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>

        <div class="footer">
            Relatorio gerado automaticamente pelo sistema Cerape.
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\cerape\resources\views\pdf\auto-avaliacao-report.blade.php ENDPATH**/ ?>