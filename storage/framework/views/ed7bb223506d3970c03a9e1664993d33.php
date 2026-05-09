<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Relatorio de avaliacao pessoal</title>
    <style>
        * { box-sizing: border-box; }
        body { color: #111827; font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.45; margin: 0; }
        .page { padding: 28px; }
        .header { border-bottom: 2px solid #d97706; display: table; padding-bottom: 18px; width: 100%; }
        .avatar-wrap { display: table-cell; vertical-align: top; width: 92px; }
        .avatar { border: 2px solid #e5e7eb; border-radius: 50%; height: 78px; object-fit: cover; width: 78px; }
        .avatar-empty { background: #f3f4f6; border: 2px solid #e5e7eb; border-radius: 50%; color: #6b7280; height: 78px; padding-top: 27px; text-align: center; width: 78px; }
        .title-wrap { display: table-cell; vertical-align: top; }
        h1 { font-size: 22px; margin: 0 0 6px; }
        h2 { color: #92400e; font-size: 15px; margin: 22px 0 10px; }
        .muted { color: #6b7280; }
        .cards { display: table; margin-top: 18px; width: 100%; }
        .card { border: 1px solid #e5e7eb; display: table-cell; padding: 12px; width: 33.33%; }
        .card + .card { border-left: 0; }
        .card-label { color: #6b7280; font-size: 10px; text-transform: uppercase; }
        .card-value { font-size: 18px; font-weight: bold; margin-top: 4px; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #f9fafb; color: #4b5563; font-size: 10px; text-align: left; text-transform: uppercase; }
        th, td { border: 1px solid #e5e7eb; padding: 7px; vertical-align: top; }
        .badge { border-radius: 999px; display: inline-block; font-weight: bold; padding: 3px 8px; }
        .success { background: #dcfce7; color: #166534; }
        .warning { background: #fef3c7; color: #92400e; }
        .danger { background: #fee2e2; color: #991b1b; }
        .gray { background: #f3f4f6; color: #374151; }
        .user-line { display: table; width: 100%; }
        .user-photo { display: table-cell; width: 38px; }
        .user-photo img, .user-initial { border-radius: 50%; height: 30px; object-fit: cover; width: 30px; }
        .user-initial { background: #f3f4f6; color: #4b5563; font-weight: bold; padding-top: 6px; text-align: center; }
        .user-info { display: table-cell; vertical-align: middle; }
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
                <h1>Relatorio completo de avaliacao pessoal</h1>
                <div class="muted">Acolhido: <strong><?php echo e($acolhido?->nome_completo_paciente ?? '-'); ?></strong></div>
                <div class="muted">Tempo de casa: <?php echo e($record->dias_na_casa ?? '-'); ?></div>
                <div class="muted">Emitido em: <?php echo e(now()->format('d/m/Y H:i')); ?></div>
            </div>
        </div>

        <div class="cards">
            <div class="card">
                <div class="card-label">Media de todos</div>
                <div class="card-value"><?php echo e($formatScore((float) $mediaDeTodos)); ?></div>
            </div>
            <div class="card">
                <div class="card-label">Usuarios avaliadores</div>
                <div class="card-value"><?php echo e($totalAvaliadores); ?></div>
            </div>
            <div class="card">
                <div class="card-label">Avaliacoes registradas</div>
                <div class="card-value"><?php echo e($avaliacoes->count()); ?></div>
            </div>
        </div>

        <h2>Resumo por usuario avaliador</h2>
        <table>
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Qtd.</th>
                    <th>Media</th>
                    <th>Controle</th>
                    <th>Autonomia</th>
                    <th>Transparencia</th>
                    <th>Superacao</th>
                    <th>Autocuidado</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="user-line">
                                <div class="user-photo">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['foto']): ?>
                                        <img src="<?php echo e($item['foto']); ?>" alt="">
                                    <?php else: ?>
                                        <div class="user-initial"><?php echo e(str($item['user']?->name ?? '?')->substr(0, 1)->upper()); ?></div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div class="user-info">
                                    <strong><?php echo e($item['user']?->name ?? 'Usuario nao informado'); ?></strong><br>
                                    <span class="muted"><?php echo e($item['user']?->email); ?></span>
                                </div>
                            </div>
                        </td>
                        <td><?php echo e($item['quantidade']); ?></td>
                        <td>
                            <span class="badge <?php echo e($scoreColor((float) $item['media'])); ?>">
                                <?php echo e($formatScore((float) $item['media'])); ?>

                            </span>
                        </td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $item['criterios']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td><?php echo e($formatScore((float) $media)); ?></td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8">Nenhuma avaliacao registrada.</td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>

        <h2>Avaliacoes detalhadas</h2>
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Usuario</th>
                    <th>Controle</th>
                    <th>Autonomia</th>
                    <th>Transparencia</th>
                    <th>Superacao</th>
                    <th>Autocuidado</th>
                    <th>Media</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $avaliacoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $avaliacao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($avaliacao->created_at?->format('d/m/Y H:i')); ?></td>
                        <td><?php echo e($avaliacao->user?->name ?? '-'); ?></td>
                        <td><?php echo e($formatScore((float) $avaliacao->controler)); ?></td>
                        <td><?php echo e($formatScore((float) $avaliacao->autonomia)); ?></td>
                        <td><?php echo e($formatScore((float) $avaliacao->transparencia)); ?></td>
                        <td><?php echo e($formatScore((float) $avaliacao->superacao)); ?></td>
                        <td><?php echo e($formatScore((float) $avaliacao->autocuidado)); ?></td>
                        <td><?php echo e($formatScore((float) $avaliacao->Total)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>

        <div class="footer">
            Relatorio gerado automaticamente pelo sistema Cerape. A pontuacao maxima de cada criterio e 3.
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\cerape\resources\views/pdf/avaliacao-pessoal-report.blade.php ENDPATH**/ ?>