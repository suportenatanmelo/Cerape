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
    <style>
        .declaration-layout {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: minmax(18rem, 24rem) minmax(0, 1fr);
        }

        .declaration-panel,
        .declaration-preview {
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(248,250,252,0.96));
            border: 1px solid rgba(148, 163, 184, 0.22);
            border-radius: 1.5rem;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }

        .declaration-panel {
            padding: 1.25rem;
        }

        .declaration-preview {
            overflow: hidden;
        }

        .declaration-preview-header {
            align-items: center;
            background: linear-gradient(135deg, #0f766e, #115e59);
            color: #f8fafc;
            display: flex;
            justify-content: space-between;
            padding: 1rem 1.25rem;
        }

        .declaration-preview-meta {
            color: rgba(248, 250, 252, 0.85);
            font-size: 0.92rem;
            margin-top: 0.25rem;
        }

        .paper-sheet {
            background: #fffef9;
            margin: 1.25rem;
            min-height: 980px;
            padding: 2.5rem 2.8rem;
            position: relative;
        }

        .paper-sheet::before {
            border: 1px solid rgba(217, 119, 6, 0.18);
            content: "";
            inset: 18px;
            pointer-events: none;
            position: absolute;
        }

        .page-note {
            color: #475569;
            font-size: 0.92rem;
            margin-top: 0.35rem;
        }

        .empty-state {
            color: #475569;
            padding: 4rem 2rem;
            text-align: center;
        }

        @media (max-width: 1100px) {
            .declaration-layout {
                grid-template-columns: 1fr;
            }

            .paper-sheet {
                margin: 0.9rem;
                padding: 1.6rem;
            }
        }
    </style>

    <div class="declaration-layout">
        <div class="declaration-panel">
            <?php echo e($this->form); ?>

        </div>

        <div class="declaration-preview">
            <div class="declaration-preview-header">
                <div>
                    <div style="font-size: 1.05rem; font-weight: 700;">Visualizacao da declaracao</div>
                    <div class="declaration-preview-meta">Documento preparado para conferencia e assinatura manual.</div>
                </div>
                <div style="font-size: 0.88rem; font-weight: 600;">CERAPE / CRC</div>
            </div>

            <?php ($payload = $this->getPreviewPayload()); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($payload): ?>
                <div class="paper-sheet">
                    <?php echo $__env->make('declaracoes.partials.documento', ['payload' => $payload, 'mode' => 'preview'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div style="font-size: 1.08rem; font-weight: 700;">Selecione uma declaracao para continuar</div>
                    <div class="page-note">Quando a declaracao exigir acolhido, escolha um nome para liberar a visualizacao e o PDF.</div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
<?php /**PATH C:\laragon\www\cerape\resources\views/filament/pages/declaracoes-assinaveis.blade.php ENDPATH**/ ?>