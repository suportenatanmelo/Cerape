<?php

namespace App\Filament\Resources\AvaliacaoPessoals\Pages;

use App\Filament\Resources\AvaliacaoPessoals\AvaliacaoPessoalResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAvaliacaoPessoal extends CreateRecord
{
    protected static string $resource = AvaliacaoPessoalResource::class;

    public function mount(): void
    {
        parent::mount();

        Notification::make()
            ->title('Atenção às notas da avaliação')
            ->body('Cada critério aceita apenas valores de 1 até 3. Revise os números informados antes de salvar.')
            ->warning()
            ->persistent()
            ->send();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        AvaliacaoPessoalResource::validateScoreData($data);
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
