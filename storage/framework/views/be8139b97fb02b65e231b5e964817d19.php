<div class="brand-bar">
    <table style="border-collapse: collapse; width: 100%;">
        <tr>
            <td style="vertical-align: top; width: 120px;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($logoCerape)): ?>
                    <img src="<?php echo e($logoCerape); ?>" class="brand-logo" alt="Logo Cerape">
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </td>
            <td style="padding-left: 12px; vertical-align: middle;">
                <div style="color: #1f2937; font-size: 10px; line-height: 1.45;">
                    <div style="font-size: 11px; font-weight: bold;">CENTRO DE REABILITACAO DO PRESO E EGRESSO - CERAPE</div>
                    <div>WHATSAPP:(61)99320841 site: www.cerape.com</div>
                    <div>CENTRO DE REABILITACAO DO CERAPE -CRC</div>
                    <div>CNPJ/SEDE: 00.857.994/0001-67 - CNPJ/FILIAL: 00.857.994/0001-48</div>
                </div>
            </td>
        </tr>
    </table>
</div>
<?php /**PATH C:\laragon\www\cerape\resources\views\pdf\partials\cerape-brand-header.blade.php ENDPATH**/ ?>