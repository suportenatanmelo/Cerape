<?php

namespace App\Filament\Resources\Acolhidos\Pages;

use App\Filament\Resources\Acolhidos\AcolhidoResource;
use App\Filament\Resources\Acolhidos\Schemas\AcolhidoForm;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateAcolhido extends CreateRecord
{
    protected static string $resource = AcolhidoResource::class;

    protected Width | string | null $maxContentWidth = Width::Full;

    public function getTitle(): string
    {
        return 'Criar Acolhido';
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Acolhido cadastrado com sucesso';
    }

    protected function afterCreate(): void
    {
        AcolhidoForm::notifyUsers($this->getRecord(), 'created');
    }
}
