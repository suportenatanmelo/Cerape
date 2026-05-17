<?php

namespace App\Filament\Widgets;

use App\Support\PortalContext;
use Filament\Widgets\Widget;

class FamilyDashboardGalleryWidget extends Widget
{
    protected string $view = 'filament.widgets.family-dashboard-gallery-widget';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = auth()->user();

        if (! PortalContext::isFamilyUser($user) || ! $user?->acolhido) {
            return false;
        }

        return (bool) $user->acolhido->acolhidoGaleria?->ativo
            && ($user->acolhido->acolhidoGaleria?->galleryCount() > 0);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $user = auth()->user();
        $acolhido = $user?->acolhido;
        $gallery = $acolhido?->acolhidoGaleria;

        return [
            'acolhido' => $acolhido,
            'gallery' => $gallery,
            'imageUrls' => $gallery?->galleryUrls() ?? [],
            'galleryTimeline' => $gallery?->galleryTimeline() ?? [],
        ];
    }
}
