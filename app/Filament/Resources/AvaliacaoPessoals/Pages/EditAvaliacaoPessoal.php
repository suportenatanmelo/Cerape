<?php

namespace App\Filament\Resources\AvaliacaoPessoals\Pages;

use App\Filament\Resources\AvaliacaoPessoals\AvaliacaoPessoalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAvaliacaoPessoal extends EditRecord
{
    protected static string $resource = AvaliacaoPessoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['dias_na_casa'] = AvaliacaoPessoalResource::calculateDiasNaCasa($data['acolhido_id'] ?? null);
        $data['Total'] = AvaliacaoPessoalResource::calculateTotal($data);

        return $data;
    }

    protected function afterSave(): void
    {
        AvaliacaoPessoalResource::notifyUsersAboutEvaluation($this->getRecord());
    }
}
