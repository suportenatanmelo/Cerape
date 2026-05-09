<?php

namespace App\Filament\Resources\AvaliacaoPessoals\Pages;

use App\Filament\Resources\AvaliacaoPessoals\AvaliacaoPessoalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAvaliacaoPessoal extends CreateRecord
{
    protected static string $resource = AvaliacaoPessoalResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] ??= auth()->id();
        $data['dias_na_casa'] = AvaliacaoPessoalResource::calculateDiasNaCasa($data['acolhido_id'] ?? null);
        $data['Total'] = AvaliacaoPessoalResource::calculateTotal($data);

        return $data;
    }

    protected function afterCreate(): void
    {
        AvaliacaoPessoalResource::notifyUsersAboutEvaluation($this->getRecord());
    }
}
