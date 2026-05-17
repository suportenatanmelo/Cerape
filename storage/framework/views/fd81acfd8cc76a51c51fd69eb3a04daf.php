<?php
    /** @var \App\Models\AcolhidoGaleria $record */
    $record = $getRecord();
    $timeline = $record->galleryTimeline();
    $carouselId = 'institutional-gallery-' . $record->getKey();
    $coverImages = collect($timeline)
        ->flatMap(fn (array $group): array => $group['images'])
        ->values();
?>

<article class="overflow-hidden rounded-[2rem] border border-neutral-900 bg-[#f7f4ee] text-neutral-950 shadow-[0_24px_60px_rgba(0,0,0,0.08)]">
    <div class="border-b border-neutral-900 px-6 py-5">
        <p class="text-[10px] font-semibold uppercase tracking-[0.38em] text-neutral-500">
            Galeria do portal
        </p>
        <div class="mt-3 flex items-start justify-between gap-4">
            <div>
                <h3 class="font-serif text-2xl leading-tight text-neutral-950">
                    <?php echo e($record->titulo ?: $record->acolhido?->nome_completo_paciente); ?>

                </h3>
                <p class="mt-2 text-sm uppercase tracking-[0.2em] text-neutral-500">
                    <?php echo e($record->acolhido?->nome_completo_paciente); ?>

                </p>
            </div>
            <span class="rounded-full border border-neutral-900 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.24em] text-neutral-700">
                <?php echo e($record->galleryCount()); ?> fotos
            </span>
        </div>
    </div>

    <div class="space-y-6 px-6 py-6">
        <div class="flex items-center justify-between gap-3">
            <p class="text-[10px] uppercase tracking-[0.24em] text-neutral-500">
                Miniaturas em carrossel
            </p>
            <div class="flex items-center gap-2">
                <button
                    type="button"
                    class="rounded-full border border-neutral-900 px-3 py-2 text-[10px] font-semibold uppercase tracking-[0.24em] text-neutral-700 transition hover:bg-neutral-900 hover:text-white"
                    onclick="document.getElementById('<?php echo e($carouselId); ?>').scrollBy({ left: -260, behavior: 'smooth' })"
                >
                    Prev
                </button>
                <button
                    type="button"
                    class="rounded-full border border-neutral-900 px-3 py-2 text-[10px] font-semibold uppercase tracking-[0.24em] text-neutral-700 transition hover:bg-neutral-900 hover:text-white"
                    onclick="document.getElementById('<?php echo e($carouselId); ?>').scrollBy({ left: 260, behavior: 'smooth' })"
                >
                    Next
                </button>
            </div>
        </div>

        <div id="<?php echo e($carouselId); ?>" class="image-gallery flex snap-x snap-mandatory gap-3 overflow-x-auto pb-2 pr-2" data-viewer-gallery>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $coverImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <figure class="w-36 shrink-0 snap-start overflow-hidden rounded-[1.25rem] border border-neutral-900 bg-white">
                    <img
                        src="<?php echo e($image['url']); ?>"
                        alt="<?php echo e($image['caption']); ?>"
                        class="aspect-[4/5] w-full cursor-zoom-in object-cover grayscale"
                        loading="lazy"
                    />
                    <figcaption class="border-t border-neutral-900 px-3 py-2 text-[11px] text-neutral-600">
                        <?php echo e($image['caption']); ?>

                    </figcaption>
                </figure>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="grid grid-cols-2 gap-4 border-t border-dashed border-neutral-400 pt-4 text-sm text-neutral-700">
            <div>
                <p class="text-[10px] uppercase tracking-[0.24em] text-neutral-500">Ultima adicao</p>
                <p class="mt-1 font-medium text-neutral-900"><?php echo e($record->lastGalleryUpdateLabel() ?: '-'); ?></p>
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-[0.24em] text-neutral-500">Periodos</p>
                <p class="mt-1 font-medium text-neutral-900"><?php echo e($record->galleryPeriodsCount()); ?></p>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($record->descricao)): ?>
            <blockquote class="border-l-2 border-neutral-900 pl-4 font-serif text-base italic leading-relaxed text-neutral-700">
                <?php echo e($record->descricao); ?>

            </blockquote>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($timeline !== []): ?>
            <div class="space-y-2 border-t border-neutral-900 pt-4">
                <p class="text-[10px] uppercase tracking-[0.24em] text-neutral-500">Linha do tempo</p>
                <div class="space-y-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = collect($timeline)->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between gap-4 text-sm">
                            <span class="font-medium text-neutral-900"><?php echo e($group['label']); ?></span>
                            <span class="text-neutral-500"><?php echo e(count($group['images'])); ?> imagem(ns)</span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</article>
<?php /**PATH C:\laragon\www\cerape\resources\views\filament\tables\columns\acolhido-galeria-card.blade.php ENDPATH**/ ?>