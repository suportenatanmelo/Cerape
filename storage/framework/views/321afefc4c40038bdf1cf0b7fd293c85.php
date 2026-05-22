<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Relatorio consolidado de avaliacao pessoal</title>
    <style>
        * { box-sizing: border-box; }
        body { background: #f8fafc; color: #0f172a; font-family: DejaVu Sans, sans-serif; font-size: 9px; line-height: 1.4; margin: 0; }
        .page { padding: 14px; }
        .brand-header { background: #fff; border: 1px solid #dbe4ea; border-radius: 12px; margin-bottom: 12px; padding: 10px 12px; }
        .brand-logo-cell { vertical-align: middle; width: 86px; }
        .brand-logo { display: block; height: auto; margin: 0 auto; max-height: 54px; max-width: 54px; }
        .brand-text-cell { padding-left: 10px; vertical-align: middle; }
        .brand-title { color: #0f172a; font-size: 11px; font-weight: bold; margin-bottom: 3px; }
        .brand-text { color: #475569; font-size: 8px; line-height: 1.45; }
        .hero { background: linear-gradient(135deg, #0f766e, #134e4a); border-radius: 14px; color: #fff; margin-bottom: 12px; padding: 16px; }
        .eyebrow { font-size: 8px; font-weight: bold; letter-spacing: 0.14em; text-transform: uppercase; }
        .title { font-size: 17px; font-weight: bold; margin: 6px 0 4px; }
        .subtitle { color: rgba(255, 255, 255, 0.88); font-size: 9px; margin: 2px 0; }
        .cards { margin: 0 0 12px; width: 100%; }
        .cards td { padding: 0 5px; vertical-align: top; width: 25%; }
        .card { background: #fff; border: 1px solid #dbe4ea; border-radius: 12px; min-height: 74px; padding: 10px; }
        .card-label { color: #64748b; display: block; font-size: 8px; font-weight: bold; letter-spacing: 0.08em; text-transform: uppercase; }
        .card-value { color: #0f172a; display: block; font-size: 14px; font-weight: bold; margin-top: 5px; }
        .card-note { color: #475569; display: block; font-size: 8px; margin-top: 4px; }
        .section-title { color: #0f172a; font-size: 10px; font-weight: bold; margin: 12px 0 6px; text-transform: uppercase; }
        .panel { background: #fff; border: 1px solid #dbe4ea; border-radius: 12px; overflow: hidden; }
        table { border-collapse: separate; border-spacing: 0; width: 100%; }
        th { background: #ecfeff; color: #0f172a; font-size: 8px; font-weight: bold; letter-spacing: 0.08em; padding: 7px; text-align: left; text-transform: uppercase; }
        td { border-bottom: 1px solid #e5e7eb; padding: 7px; vertical-align: top; }
        tbody tr:nth-child(even) td { background: #fafcfc; }
        tbody tr:last-child td { border-bottom: none; }
        .center { text-align: center; }
        .name { font-size: 9px; font-weight: bold; }
        .muted { color: #64748b; font-size: 8px; }
        .score { color: #0f766e; font-weight: bold; }
        .chip { background: #e2e8f0; border-radius: 999px; color: #334155; display: inline-block; font-size: 8px; font-weight: bold; padding: 3px 8px; }
        .formula-box { background: #fff; border: 1px solid #dbe4ea; border-radius: 12px; margin-top: 12px; padding: 10px 12px; }
        .formula-title { color: #0f172a; font-size: 9px; font-weight: bold; margin-bottom: 6px; text-transform: uppercase; }
        .formula-line { color: #334155; font-size: 8px; margin: 3px 0; }
        .footer { border-top: 1px solid #dbe4ea; color: #64748b; font-size: 8px; margin-top: 10px; padding-top: 6px; text-align: center; }
    </style>
</head>
<body>
    <div class="page">
        <div class="brand-header">
            <table>
                <tr>
                    <td class="brand-logo-cell">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($logoCerape)): ?>
                            <img src="<?php echo e($logoCerape); ?>" class="brand-logo" alt="Logo Cerape">
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>
                    <td class="brand-text-cell">
                        <div class="brand-title">CENTRO DE REABILITACAO DO PRESO E EGRESSO - CERAPE</div>
                        <div class="brand-text">Relatorio institucional consolidado para acompanhamento de acolhidos avaliados.</div>
                        <div class="brand-text">WhatsApp: (61) 99320-841 | Site: www.cerape.com</div>
                        <div class="brand-text">CNPJ sede: 00.857.994/0001-67 | CNPJ filial: 00.857.994/0001-48</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="hero">
            <div class="eyebrow">Avaliacao pessoal consolidada</div>
            <div class="title">Relatorio geral de acolhidos avaliados</div>
            <div class="subtitle">Gerado em: <?php echo e($generatedAt->format('d/m/Y H:i')); ?></div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedDate): ?>
                <div class="subtitle">Filtro aplicado: <?php echo e($selectedDate->format('d/m/Y')); ?></div>
            <?php else: ?>
                <div class="subtitle">Periodo analisado: historico completo das avaliacoes registradas.</div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="subtitle">O documento mostra todos os acolhidos que receberam votos, a quantidade total de votos e a media consolidada dos usuarios que avaliaram.</div>
        </div>

        <table class="cards">
            <tr>
                <td>
                    <div class="card">
                        <span class="card-label">Acolhidos avaliados</span>
                        <span class="card-value"><?php echo e($totalAcolhidos); ?></span>
                        <span class="card-note">Cadastros com pelo menos uma avaliacao.</span>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <span class="card-label">Usuarios que votaram</span>
                        <span class="card-value"><?php echo e($totalProfissionais); ?></span>
                        <span class="card-note">Avaliadores unicos considerados no relatorio.</span>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <span class="card-label">Quantidade geral de votos</span>
                        <span class="card-value"><?php echo e($totalVotos); ?></span>
                        <span class="card-note">Total de lancamentos usados no calculo.</span>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <span class="card-label">Media geral consolidada</span>
                        <span class="card-value"><?php echo e($formatScore((float) $overallMediaUsuarios)); ?></span>
                        <span class="card-note">Media final baseada nas medias dos usuarios que votaram.</span>
                    </div>
                </td>
            </tr>
        </table>

        <div class="section-title">Consolidado por acolhido</div>

        <div class="panel">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 23%;">Acolhido</th>
                        <th style="width: 20%;">Usuarios que votaram</th>
                        <th style="width: 11%;">Votos</th>
                        <th style="width: 14%;">Media dos votos</th>
                        <th style="width: 14%;">Media de todos</th>
                        <th style="width: 13%;">Formula</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="center"><?php echo e($index + 1); ?></td>
                            <td>
                                <div class="name"><?php echo e($row['acolhido_nome']); ?></div>
                                <div class="muted">Primeira avaliacao: <?php echo e($row['primeira_avaliacao_em']?->format('d/m/Y H:i') ?? '-'); ?></div>
                                <div class="muted">Ultima avaliacao: <?php echo e($row['ultima_avaliacao_em']?->format('d/m/Y H:i') ?? '-'); ?></div>
                            </td>
                            <td>
                                <div><?php echo e($row['profissional_nome']); ?></div>
                                <div class="muted"><?php echo e($row['total_avaliadores']); ?> usuario(s) avaliador(es)</div>
                            </td>
                            <td class="center"><span class="chip"><?php echo e($row['total_votos']); ?></span></td>
                            <td class="score center"><?php echo e($formatScore((float) $row['media_geral_votos'])); ?></td>
                            <td class="score center"><?php echo e($formatScore((float) $row['media_de_todos'])); ?></td>
                            <td class="center"><?php echo e($row['formula_texto']); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="formula-box">
            <div class="formula-title">Explicacao simples da media de todos</div>
            <div class="formula-line">1. Pegamos todas as notas dadas por cada usuario para o mesmo acolhido.</div>
            <div class="formula-line">2. Calculamos a media individual de cada usuario.</div>
            <div class="formula-line">3. Somamos essas medias individuais.</div>
            <div class="formula-line">4. Dividimos essa soma pela quantidade de usuarios que votaram.</div>
            <div class="formula-line"><strong>Em palavras:</strong> a media de todos e a soma das medias de cada avaliador dividida pela quantidade de avaliadores.</div>
        </div>

        <div class="footer">
            Relatorio gerado automaticamente pelo Sistema Cerape para acompanhamento institucional.
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\cerape\resources\views/pdf/avaliacao-pessoal-consolidado-report.blade.php ENDPATH**/ ?>