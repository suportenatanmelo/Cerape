<?php

namespace App\Filament\Resources\GeradorAtividades\Pages;

use App\Filament\Resources\GeradorAtividades\GeradorAtividadeResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateGeradorAtividade extends CreateRecord
{
    protected static string $resource = GeradorAtividadeResource::class;

    protected Width | string | null $maxContentWidth = Width::SevenExtraLarge;

    public function getTitle(): string
    {
        return 'Nova programacao de atividades';
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] ??= auth()->id();

        return $data;
    }
}
