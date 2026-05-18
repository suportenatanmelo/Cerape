<div class="favorite-list-item">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user): ?>
        <div data-id="<?php echo e($user->id); ?>" data-action="0" class="avatar av-m"
            style="background-image: url('<?php echo e(Chatify::getUserWithAvatar($user)->avatar); ?>');">
        </div>
        <p><?php echo e(strlen($user->name) > 5 ? substr($user->name,0,6).'..' : $user->name); ?></p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\cerape\vendor\munafio\chatify\src\views\layouts\favorite.blade.php ENDPATH**/ ?>