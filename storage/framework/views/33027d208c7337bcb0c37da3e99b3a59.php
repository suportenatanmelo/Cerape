<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'images' => [],
    'emptyText' => null,
    'thumbWidth' => 128,
    'thumbHeight' => 128,
    'rounded' => 'rounded-lg',
    'gap' => 'gap-4',
    'wrapperClass' => '',
    'zoomCursor' => true,
    'id' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'images' => [],
    'emptyText' => null,
    'thumbWidth' => 128,
    'thumbHeight' => 128,
    'rounded' => 'rounded-lg',
    'gap' => 'gap-4',
    'wrapperClass' => '',
    'zoomCursor' => true,
    'id' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $galleryId = $id ?? 'gallery-' . str_replace(['{', '}', '-'], '', (string) \Illuminate\Support\Str::uuid());
    $urls = collect($images)
        ->map(function ($item) {
            if (is_string($item)) {
                return $item;
            }
            if (is_array($item)) {
                return $item['image'] ?? ($item['url'] ?? null);
            }
            if (is_object($item)) {
                return $item->image ?? ($item->url ?? null);
            }
            return null;
        })
        ->filter()
        ->values();
    $emptyTextDisplay = $emptyText ?? __('image-gallery::messages.empty');
?>

<div id="<?php echo e($galleryId); ?>"
    class="fi-in-image image-gallery <?php echo e($gap); ?> my-4 pb-2 select-none <?php echo e($wrapperClass); ?>"
    style="display: flex !important; flex-direction: row !important; flex-wrap: nowrap !important; overflow-x: auto; max-width: 100%; scrollbar-width: thin; cursor: pointer;"
    data-viewer-gallery>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $urls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $src): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <img src="<?php echo e($src); ?>" loading="lazy"
            class="<?php echo e($rounded); ?> shadow object-cover border border-gray-200 dark:border-gray-700 hover:scale-105 transition <?php echo e($zoomCursor ? 'cursor-zoom-in' : ''); ?>"
            style="width: <?php echo e((int) $thumbWidth); ?>px; height: <?php echo e((int) $thumbHeight); ?>px; flex-shrink: 0;"
            alt="image" />
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <span class="text-gray-400 dark:text-gray-500"><?php echo e($emptyTextDisplay); ?></span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>


<?php /**PATH C:\laragon\www\cerape\vendor\al-saloul\filament-image-gallery\resources\views\components\image-gallery.blade.php ENDPATH**/ ?>