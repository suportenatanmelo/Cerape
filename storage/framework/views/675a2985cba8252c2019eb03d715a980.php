<?php
    $context = $context ?? 'admin';
    $isFamily = $context === 'family';
    $wrapperClasses = $isFamily
        ? 'family-gallery-shell overflow-hidden rounded-[2rem] border border-[#1f2937] bg-[linear-gradient(180deg,#f8f4ec_0%,#f3ede3_100%)] text-neutral-950 shadow-[0_30px_90px_rgba(15,23,42,0.14)]'
        : 'overflow-hidden rounded-[2rem] border border-slate-200 bg-white text-slate-950 shadow-[0_20px_60px_rgba(15,23,42,0.08)]';
    $headerBorder = $isFamily ? 'border-neutral-900' : 'border-slate-200';
    $eyebrowClass = $isFamily
        ? 'text-[11px] font-semibold uppercase tracking-[0.34em] text-neutral-500'
        : 'text-[11px] font-semibold uppercase tracking-[0.34em] text-slate-500';
    $titleClass = $isFamily
        ? 'font-serif text-[2.15rem] leading-[1.05] text-neutral-950'
        : 'font-serif text-3xl leading-tight text-slate-950';
    $descriptionClass = $isFamily
        ? 'mt-3 max-w-2xl text-[0.95rem] leading-7 text-neutral-700'
        : 'mt-2 max-w-2xl text-sm leading-relaxed text-slate-600';
    $badgeClass = $isFamily
        ? 'rounded-full border border-neutral-900 bg-white/70 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-neutral-700 shadow-sm backdrop-blur'
        : 'rounded-full border border-slate-300 bg-slate-50 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-700';
    $sectionRuleClass = $isFamily ? 'border-neutral-400' : 'border-slate-300';
    $sectionTitleClass = $isFamily
        ? 'text-sm font-semibold uppercase tracking-[0.28em] text-neutral-500'
        : 'text-sm font-semibold uppercase tracking-[0.28em] text-slate-500';
    $sectionDescriptionClass = $isFamily ? 'mt-1 text-sm text-neutral-600' : 'mt-1 text-sm text-slate-600';
    $buttonClass = $isFamily
        ? 'rounded-full border border-neutral-900 bg-white/75 px-3 py-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-neutral-700 transition hover:-translate-y-0.5 hover:bg-neutral-900 hover:text-white'
        : 'rounded-full border border-slate-300 bg-white px-3 py-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-700 transition hover:border-slate-900 hover:bg-slate-900 hover:text-white';
    $figureClass = $isFamily
        ? 'family-gallery-card w-[18.75rem] shrink-0 snap-start overflow-hidden rounded-[1.45rem] border border-[#111827] bg-white shadow-[0_22px_44px_rgba(15,23,42,0.14)]'
        : 'w-[17.5rem] shrink-0 snap-start overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-[0_16px_36px_rgba(15,23,42,0.10)]';
    $captionClass = $isFamily
        ? 'border-t border-neutral-900/80 bg-[#fffdf8] px-4 py-3 font-serif text-sm italic text-neutral-700'
        : 'border-t border-slate-200 px-4 py-3 font-serif text-sm italic text-slate-600';
    $statCardClass = $isFamily
        ? 'rounded-[1.4rem] border border-neutral-900/70 bg-white/75 p-4 shadow-sm backdrop-blur'
        : 'rounded-[1.4rem] border border-slate-200 bg-slate-50/90 p-4 shadow-sm';
    $statLabelClass = $isFamily
        ? 'text-[11px] font-semibold uppercase tracking-[0.24em] text-neutral-500'
        : 'text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-500';
    $statValueClass = $isFamily
        ? 'mt-2 font-serif text-3xl text-neutral-950'
        : 'mt-2 font-serif text-3xl text-slate-950';
    $chipClass = $isFamily
        ? 'inline-flex items-center rounded-full border border-neutral-900/80 bg-white/70 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.22em] text-neutral-700'
        : 'inline-flex items-center rounded-full border border-slate-300 bg-white px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.22em] text-slate-600';
    $totalImages = count($imageUrls ?? []);
    $periodCount = count($galleryTimeline ?? []);
    $latestUpdate = $gallery?->lastGalleryUpdateLabel();
?>

<?php if (! $__env->hasRenderedOnce('1de9f6dc-9d1a-4855-8810-509b6c20e7ca')): $__env->markAsRenderedOnce('1de9f6dc-9d1a-4855-8810-509b6c20e7ca'); ?>
    <?php $__env->startPush('styles'); ?>
        <style>
            .family-gallery-shell {
                position: relative;
            }

            .family-gallery-shell::before {
                background:
                    radial-gradient(circle at top right, rgba(217, 119, 6, 0.10), transparent 22rem),
                    radial-gradient(circle at left top, rgba(15, 118, 110, 0.12), transparent 20rem);
                content: "";
                inset: 0;
                pointer-events: none;
                position: absolute;
            }

            .family-gallery-card {
                transition: transform 220ms ease, box-shadow 220ms ease, filter 220ms ease;
            }

            .family-gallery-card:hover {
                box-shadow: 0 28px 56px rgba(15, 23, 42, 0.18);
                transform: translateY(-4px);
            }

            .gallery-timeline-strip::-webkit-scrollbar {
                height: 10px;
            }

            .gallery-timeline-strip::-webkit-scrollbar-thumb {
                background: rgba(100, 116, 139, 0.35);
                border-radius: 999px;
            }
        </style>
    <?php $__env->stopPush(); ?>
<?php endif; ?>

<div class="<?php echo e($wrapperClasses); ?>">
    <div class="relative border-b <?php echo e($headerBorder); ?> px-6 py-6 sm:px-8">
        <p class="<?php echo e($eyebrowClass); ?>">
            <?php echo e($isFamily ? 'Dashboard da familia' : 'Dashboard institucional'); ?>

        </p>
        <div class="mt-3 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <h2 class="<?php echo e($titleClass); ?>">
                    <?php echo e($gallery?->titulo ?: 'Galeria do acolhido'); ?>

                </h2>
                <p class="<?php echo e($descriptionClass); ?>">
                    <?php echo e($gallery?->descricao ?: 'Clique nas imagens para ampliar, navegar, dar zoom e visualizar em tela cheia.'); ?>

                </p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="<?php echo e($chipClass); ?>">
                        <?php echo e($periodCount); ?> momentos
                    </span>
                    <span class="<?php echo e($chipClass); ?>">
                        <?php echo e($totalImages); ?> imagens
                    </span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($gallery?->ativo): ?>
                        <span class="<?php echo e($chipClass); ?>">
                            Album ativo no portal
                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($latestUpdate)): ?>
                    <p class="mt-3 text-[11px] font-semibold uppercase tracking-[0.24em] <?php echo e($isFamily ? 'text-neutral-500' : 'text-slate-500'); ?>">
                        Ultima atualizacao: <?php echo e($latestUpdate); ?>

                    </p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="<?php echo e($badgeClass); ?>">
                <?php echo e($totalImages); ?> imagens de <?php echo e($acolhido?->nome_completo_paciente); ?>

            </div>
        </div>
    </div>

    <div class="relative space-y-8 px-6 py-6 sm:px-8">
        <div class="grid gap-4 lg:grid-cols-3">
            <div class="<?php echo e($statCardClass); ?>">
                <p class="<?php echo e($statLabelClass); ?>">Acervo visual</p>
                <p class="<?php echo e($statValueClass); ?>"><?php echo e($totalImages); ?></p>
                <p class="mt-2 text-sm <?php echo e($isFamily ? 'text-neutral-600' : 'text-slate-600'); ?>">
                    Fotografias e registros disponiveis neste album.
                </p>
            </div>
            <div class="<?php echo e($statCardClass); ?>">
                <p class="<?php echo e($statLabelClass); ?>">Linha do tempo</p>
                <p class="<?php echo e($statValueClass); ?>"><?php echo e($periodCount); ?></p>
                <p class="mt-2 text-sm <?php echo e($isFamily ? 'text-neutral-600' : 'text-slate-600'); ?>">
                    Datas agrupadas para leitura cronologica do material.
                </p>
            </div>
            <div class="<?php echo e($statCardClass); ?>">
                <p class="<?php echo e($statLabelClass); ?>">Ultimo movimento</p>
                <p class="mt-2 font-serif text-2xl <?php echo e($isFamily ? 'text-neutral-950' : 'text-slate-950'); ?>">
                    <?php echo e($latestUpdate ?: '--'); ?>

                </p>
                <p class="mt-2 text-sm <?php echo e($isFamily ? 'text-neutral-600' : 'text-slate-600'); ?>">
                    Registro mais recente publicado nesta galeria.
                </p>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = ($galleryTimeline ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $galleryGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <section class="space-y-4 rounded-[1.6rem] border <?php echo e($isFamily ? 'border-neutral-900/15 bg-white/35' : 'border-slate-200 bg-slate-50/55'); ?> p-4 sm:p-5">
                <div class="flex items-center justify-between gap-3 border-b border-dashed <?php echo e($sectionRuleClass); ?> pb-3">
                    <div>
                        <h3 class="<?php echo e($sectionTitleClass); ?>">
                            <?php echo e($galleryGroup['label']); ?>

                        </h3>
                        <p class="<?php echo e($sectionDescriptionClass); ?>">
                            <?php echo e(count($galleryGroup['images'])); ?> imagem(ns) adicionada(s) nesta data
                        </p>
                    </div>

                    <?php
                        $galleryId = 'gallery-timeline-' . str_replace(['{', '}', '-'], '', (string) \Illuminate\Support\Str::uuid());
                    ?>

                    <div class="hidden items-center gap-2 sm:flex">
                        <button
                            type="button"
                            class="<?php echo e($buttonClass); ?>"
                            onclick="document.getElementById('<?php echo e($galleryId); ?>').scrollBy({ left: -320, behavior: 'smooth' })"
                        >
                            Prev
                        </button>
                        <button
                            type="button"
                            class="<?php echo e($buttonClass); ?>"
                            onclick="document.getElementById('<?php echo e($galleryId); ?>').scrollBy({ left: 320, behavior: 'smooth' })"
                        >
                            Next
                        </button>
                    </div>
                </div>

                <div
                    id="<?php echo e($galleryId); ?>"
                    class="gallery-timeline-strip image-gallery family-gallery-dashboard flex snap-x snap-mandatory gap-5 overflow-x-auto pb-3 pr-2"
                    data-viewer-gallery
                >
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $galleryGroup['images']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <figure class="<?php echo e($figureClass); ?>">
                            <div class="relative aspect-video overflow-hidden bg-neutral-900">
                                <div class="absolute inset-x-0 top-0 z-[1] h-20 bg-gradient-to-b from-black/28 to-transparent"></div>
                                <img
                                    src="<?php echo e($image['url']); ?>"
                                    alt="<?php echo e($image['caption']); ?>"
                                    loading="lazy"
                                    class="h-full w-full cursor-zoom-in object-cover transition duration-500 hover:scale-[1.04]"
                                    data-added-at="<?php echo e($image['added_at']); ?>"
                                />
                                <div class="absolute left-3 top-3 z-[2] rounded-full bg-white/88 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.22em] text-neutral-800 shadow-sm">
                                    CERAPE CRC
                                </div>
                                <div class="absolute inset-x-0 bottom-0 z-[2] flex items-end justify-between gap-3 bg-gradient-to-t from-black/60 via-black/10 to-transparent px-4 py-3">
                                    <span class="rounded-full bg-white/90 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.22em] text-neutral-800 shadow-sm">
                                        Visualizar
                                    </span>
                                    <span class="text-[10px] font-medium uppercase tracking-[0.18em] text-white/90">
                                        <?php echo e($galleryGroup['label']); ?>

                                    </span>
                                </div>
                            </div>
                            <figcaption class="<?php echo e($captionClass); ?>">
                                <?php echo e($image['caption']); ?>

                            </figcaption>
                        </figure>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </section>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div class="rounded-[1.5rem] border border-dashed <?php echo e($isFamily ? 'border-neutral-400 bg-white/70 text-neutral-600' : 'border-slate-300 bg-slate-50 text-slate-600'); ?> px-6 py-10 text-center">
                Nenhuma imagem disponivel nesta galeria.
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\laragon\www\cerape\resources\views/filament/resources/acolhido-galerias/gallery-timeline.blade.php ENDPATH**/ ?>