<?php

namespace App\Filament\Resources\Acolhidos\Pages;

use App\Filament\Resources\Acolhidos\AcolhidoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditAcolhido extends EditRecord
{
    protected static string $resource = AcolhidoResource::class;

    protected Width | string | null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->successNotificationTitle('Acolhido excluido com sucesso'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Acolhido atualizado com sucesso';
    }
}
