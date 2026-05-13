<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Relatorio de avaliacao pessoal</title>
    <style>
        * { box-sizing: border-box; }
        body { color: #111827; font-family: DejaVu Sans, sans-serif; font-size: 10px; line-height: 1.35; margin: 0; }
        .page { padding: 20px; }
        .brand-bar { border-bottom: 1px solid #e5e7eb; margin-bottom: 14px; padding-bottom: 10px; width: 100%; }
        .brand-table { display: table; width: 100%; }
        .brand-logo-cell, .brand-info-cell { display: table-cell; vertical-align: middle; }
        .brand-logo-cell { width: 235px; padding-right: 14px; }
        .brand-logo { display: block; height: auto; max-height: 60px; max-width: 220px; }
        .brand-info-cell { text-align: left; }
        .brand-info { color: #374151; font-family: DejaVu Serif, serif; font-size: 9px; line-height: 1.45; }
        .brand-info strong { color: #111827; display: block; font-size: 10px; letter-spacing: 0.2px; margin-bottom: 2px; }
        .brand-info-line { margin: 1px 0; }
        .hero { display: table; width: 100%; }
        .hero-left, .hero-right { display: table-cell; vertical-align: top; }
        .hero-left { width: 150px; padding-right: 14px; }
        .hero-right { padding-left: 14px; }
        .profile-card { border: 1px solid #e5e7eb; padding: 12px; text-align: center; }
        .avatar { border: 2px solid #e5e7eb; border-radius: 14px; height: 148px; object-fit: cover; width: 112px; }
        .avatar-empty { background: #f3f4f6; border: 2px solid #e5e7eb; border-radius: 14px; color: #6b7280; font-size: 24px; font-weight: bold; height: 148px; line-height: 148px; margin: 0 auto; text-align: center; width: 112px; }
        .profile-name { font-size: 13px; font-weight: bold; margin-top: 8px; }
        .profile-meta { color: #6b7280; font-size: 9px; margin-top: 3px; }
        .title-panel { border-bottom: 2px solid #d97706; padding-bottom: 12px; }
        h1 { font-size: 19px; margin: 0 0 4px; }
        h2 { color: #92400e; font-size: 12px; margin: 16px 0 8px; }
        .muted { color: #6b7280; }
        .cards { display: table; margin-top: 14px; width: 100%; }
        .card { border: 1px solid #e5e7eb; display: table-cell; padding: 10px; width: 25%; }
        .card + .card { border-left: 0; }
        .card-label { color: #6b7280; font-size: 8px; text-transform: uppercase; }
        .card-value { font-size: 14px; font-weight: bold; margin-top: 3px; }
        .two-col { display: table; width: 100%; }
        .two-col-left, .two-col-right { display: table-cell; vertical-align: top; width: 50%; }
        .two-col-left { padding-right: 6px; }
        .two-col-right { padding-left: 6px; }
        .info-table td { border: 1px solid #e5e7eb; padding: 6px; vertical-align: top; }
        .info-label { background: #f9fafb; color: #4b5563; font-size: 8px; font-weight: bold; text-transform: uppercase; width: 38%; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #f9fafb; color: #4b5563; font-size: 8px; text-align: left; text-transform: uppercase; }
        th, td { border: 1px solid #e5e7eb; padding: 6px; vertical-align: top; }
        .criteria-grid { display: table; width: 100%; }
        .criteria-item { border: 1px solid #e5e7eb; display: table-cell; padding: 8px; width: 20%; }
        .criteria-label { color: #6b7280; font-size: 8px; text-transform: uppercase; }
        .criteria-value { font-size: 13px; font-weight: bold; margin-top: 3px; }
        .comparison-box { border: 1px solid #e5e7eb; margin-bottom: 8px; padding: 8px; }
        .comparison-title { font-size: 11px; font-weight: bold; margin-bottom: 3px; }
        .comparison-range { color: #6b7280; font-size: 8px; margin-bottom: 6px; }
        .evaluator-card { border: 1px solid #e5e7eb; margin-bottom: 10px; page-break-inside: avoid; }
        .evaluator-head { background: #f9fafb; border-bottom: 1px solid #e5e7eb; display: table; padding: 8px; width: 100%; }
        .evaluator-photo-wrap, .evaluator-head-content { display: table-cell; vertical-align: middle; }
        .evaluator-photo-wrap { width: 52px; }
        .evaluator-photo { border: 1px solid #d1d5db; border-radius: 50%; height: 38px; object-fit: cover; width: 38px; }
        .evaluator-photo-empty { background: #e5e7eb; border: 1px solid #d1d5db; border-radius: 50%; color: #4b5563; font-size: 11px; font-weight: bold; height: 38px; line-height: 36px; text-align: center; width: 38px; }
        .evaluator-name { font-size: 11px; font-weight: bold; }
        .evaluator-meta { color: #6b7280; font-size: 8px; }
        .evaluator-summary { display: table; width: 100%; }
        .evaluator-summary-item { display: table-cell; padding: 8px; text-align: center; width: 33.33%; }
        .evaluator-criteria { display: table; width: 100%; }
        .evaluator-criteria-item { border-top: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; display: table-cell; padding: 8px; text-align: center; width: 20%; }
        .evaluator-criteria-item:last-child { border-right: 0; }
        .evaluator-criteria-label { color: #6b7280; font-size: 8px; text-transform: uppercase; }
        .evaluator-criteria-value { font-size: 12px; font-weight: bold; margin-top: 3px; }
        .footer { border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 9px; margin-top: 18px; padding-top: 8px; }
    </style>
</head>
<body>

    <div class="page">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logoCerape): ?>
            <div class="brand-bar">
                <div class="brand-table">
                    <div class="brand-logo-cell">
                        <img src="<?php echo e($logoCerape); ?>" class="brand-logo" alt="Logo Cerape">
                    </div>
                    <div class="brand-info-cell">
                        <div class="brand-info">
                            <strong>CENTRO DE REABILITAÇÃO DO - CERAPE - CRC</strong>
                            <div class="brand-info-line">ÁREA RURAL DO PARQUE ALVORADA 3, FAZENDA QUINTAS S/N - LUZIÂNIA - GO</div>
                            <div class="brand-info-line">CEP N 72.800-010 TELEFONE (61) 3323-5403</div>
                            <div class="brand-info-line">CNPJ 00.857.994.0002-48</div>
                            <div class="brand-info-line">agendacerape@gmail.com</div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="hero">
            <div class="hero-left">
                <div class="profile-card">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fotoAcolhido): ?>
                        <img src="<?php echo e($fotoAcolhido); ?>" class="avatar" alt="">
                    <?php else: ?>
                        <div class="avatar-empty"><?php echo e(str($acolhido?->nome_completo_paciente ?? '?')->substr(0, 1)->upper()); ?></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="profile-name"><?php echo e($acolhido?->nome_completo_paciente ?? '-'); ?></div>
                    <div class="profile-meta"><?php echo e($record->dias_na_casa ?? '-'); ?></div>
                    <div class="profile-meta">Relatorio profissional de evolucao pessoal</div>
                </div>
            </div>

            <div class="hero-right">
                <div class="title-panel">
                    <h1>Relatorio detalhado de avaliacao pessoal</h1>
                    <div class="muted">Acolhido: <strong><?php echo e($acolhido?->nome_completo_paciente ?? '-'); ?></strong></div>
                    <div class="muted">Tempo de casa: <?php echo e($record->dias_na_casa ?? '-'); ?></div>
                    <div class="muted">Emitido em: <?php echo e(now()->format('d/m/Y')); ?></div>
                    <div class="muted">Ultima avaliacao registrada: <?php echo e($ultimaAvaliacao?->created_at?->format('d/m/Y') ?? '-'); ?></div>
                </div>

                <div class="cards">
                    <div class="card">
                        <div class="card-label">Media geral consolidada</div>
                        <div class="card-value"><?php echo e($formatScore((float) $mediaDeTodos)); ?></div>
                    </div>
                    <div class="card">
                        <div class="card-label">Soma das medias individuais limitada a 3</div>
                        <div class="card-value"><?php echo e(number_format((float) $somaMediasIndividuais, 2, ',', '.')); ?></div>
                    </div>
                    <div class="card">
                        <div class="card-label">Usuarios avaliadores</div>
                        <div class="card-value"><?php echo e($totalAvaliadores); ?></div>
                    </div>
                    <div class="card">
                        <div class="card-label">Avaliacoes registradas</div>
                        <div class="card-value"><?php echo e($totalAvaliacoes); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="two-col">
            <div class="two-col-left">
                <h2>Dados pessoais do acolhido</h2>
                <table class="info-table">
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = collect($personalData)->slice(0, (int) ceil(count($personalData) / 2)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="info-label"><?php echo e($item['label']); ?></td>
                                <td><?php echo e($item['value']); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="two-col-right">
                <h2>&nbsp;</h2>
                <table class="info-table">
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = collect($personalData)->slice((int) ceil(count($personalData) / 2)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="info-label"><?php echo e($item['label']); ?></td>
                                <td><?php echo e($item['value']); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <h2>Media geral por criterio</h2>
        <div class="criteria-grid">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $criteriaAverages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="criteria-item">
                    <div class="criteria-label"><?php echo e($label); ?></div>
                    <div class="criteria-value"><?php echo e($formatScore((float) $value)); ?></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <h2>Comparativos de periodo</h2>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['semanal', 'mensal', 'semestral']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comparisonKey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php ($comparison = $periodComparisons[$comparisonKey]); ?>
            <div class="comparison-box">
                <div class="comparison-title"><?php echo e($comparison['label']); ?></div>
                <div class="comparison-range"><?php echo e($comparison['current_label']); ?> comparado com <?php echo e($comparison['previous_label']); ?></div>
                <table>
                    <thead>
                        <tr>
                            <th>Metrica</th>
                            <th>Periodo atual</th>
                            <th>Periodo anterior</th>
                            <th>Variacao</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Media das avaliacoes</td>
                            <td><?php echo e($formatScore((float) $comparison['raw_current'])); ?></td>
                            <td><?php echo e($formatScore((float) $comparison['raw_previous'])); ?></td>
                            <td><?php echo e(($comparison['raw_delta'] >= 0 ? '+' : '') . number_format((float) $comparison['raw_delta'], 2, ',', '.')); ?></td>
                        </tr>
                        <tr>
                            <td>Media consolidada dos avaliadores</td>
                            <td><?php echo e($formatScore((float) $comparison['consolidated_current'])); ?></td>
                            <td><?php echo e($formatScore((float) $comparison['consolidated_previous'])); ?></td>
                            <td><?php echo e(($comparison['consolidated_delta'] >= 0 ? '+' : '') . number_format((float) $comparison['consolidated_delta'], 2, ',', '.')); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <h2>Resumo por usuario avaliador</h2>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="evaluator-card">
                <div class="evaluator-head">
                    <div class="evaluator-photo-wrap">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['foto']): ?>
                            <img src="<?php echo e($item['foto']); ?>" class="evaluator-photo" alt="">
                        <?php else: ?>
                            <div class="evaluator-photo-empty"><?php echo e(str($item['user']?->name ?? '?')->substr(0, 1)->upper()); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="evaluator-head-content">
                        <div class="evaluator-name"><?php echo e($item['user']?->name ?? 'Usuario nao informado'); ?></div>
                        <div class="evaluator-meta"><?php echo e($item['user']?->email ?? '-'); ?></div>
                    </div>
                </div>

                <div class="evaluator-summary">
                    <div class="evaluator-summary-item">
                        <div class="card-label">Qtd. votos</div>
                        <div class="card-value"><?php echo e($item['quantidade']); ?></div>
                    </div>
                    <div class="evaluator-summary-item">
                        <div class="card-label">Media individual</div>
                        <div class="card-value"><?php echo e($formatScore((float) $item['media'])); ?></div>
                    </div>
                    <div class="evaluator-summary-item">
                        <div class="card-label">Ultimo voto</div>
                        <div class="card-value" style="font-size: 11px;"><?php echo e($item['ultima_avaliacao']?->created_at?->format('d/m/Y') ?? '-'); ?></div>
                    </div>
                </div>

                <div class="evaluator-criteria">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $item['criterios']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="evaluator-criteria-item">
                            <div class="evaluator-criteria-label"><?php echo e($label); ?></div>
                            <div class="evaluator-criteria-value"><?php echo e($formatScore((float) $media)); ?></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <table>
                <tbody>
                    <tr>
                        <td>Nenhuma avaliacao registrada.</td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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
                    <th>Media final</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $avaliacoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $avaliacao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($avaliacao->created_at?->format('d/m/Y')); ?></td>
                        <td><?php echo e($avaliacao->user?->name ?? '-'); ?></td>
                        <td><?php echo e($formatScore((float) $avaliacao->controler)); ?></td>
                        <td><?php echo e($formatScore((float) $avaliacao->autonomia)); ?></td>
                        <td><?php echo e($formatScore((float) $avaliacao->transparencia)); ?></td>
                        <td><?php echo e($formatScore((float) $avaliacao->superacao)); ?></td>
                        <td><?php echo e($formatScore((float) $avaliacao->autocuidado)); ?></td>
                        <td><?php echo e($formatScore((float) $avaliacao->Total)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8">Nenhuma avaliacao registrada para este acolhido.</td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>

        <h2>Logica de calculo das medias</h2>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Explicacao</th>
                    <th>Formula</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $logicasMedias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logica): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><strong><?php echo e($logica['titulo']); ?></strong></td>
                        <td><?php echo e($logica['descricao']); ?></td>
                        <td><?php echo e($logica['formula']); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>

        <div class="footer">
            Relatorio gerado automaticamente pelo sistema Cerape. As medias foram calculadas com pontuacao maxima de 3 por criterio.
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\cerape\resources\views/pdf/avaliacao-pessoal-report.blade.php ENDPATH**/ ?>