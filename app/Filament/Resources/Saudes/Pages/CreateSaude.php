<?php

namespace App\Filament\Resources\Saudes\Pages;

use App\Filament\Resources\Saudes\SaudeResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateSaude extends CreateRecord
{
    protected static string $resource = SaudeResource::class;

    protected Width | string | null $maxContentWidth = Width::Full;

    public function getTitle(): string
    {
        return 'Nova ficha de saude';
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Ficha de saude criada com sucesso';
    }
}
