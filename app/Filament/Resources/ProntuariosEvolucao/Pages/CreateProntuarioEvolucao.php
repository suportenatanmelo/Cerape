<?php

namespace App\Filament\Resources\ProntuariosEvolucao\Pages;

use App\Filament\Resources\ProntuariosEvolucao\ProntuarioEvolucaoResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateProntuarioEvolucao extends CreateRecord
{
    protected static string $resource = ProntuarioEvolucaoResource::class;

    protected Width | string | null $maxContentWidth = Width::SevenExtraLarge;

    public function getTitle(): string
    {
        return 'Novo prontuario de evolucao';
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

    protected function afterCreate(): void
    {
        ProntuarioEvolucaoResource::notifyUsers($this->getRecord(), 'created');
    }
}
