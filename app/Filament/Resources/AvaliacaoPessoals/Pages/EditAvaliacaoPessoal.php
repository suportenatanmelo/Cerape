<?php

namespace App\Filament\Resources\AvaliacaoPessoals\Pages;

use App\Filament\Resources\AvaliacaoPessoals\AvaliacaoPessoalResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAvaliacaoPessoal extends EditRecord
{
    protected static string $resource = AvaliacaoPessoalResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);

        Notification::make()
            ->title('Atenção às notas da avaliação')
            ->body('Cada critério aceita apenas valores de 1 até 3. Revise os números informados antes de salvar.')
            ->warning()
            ->persistent()
            ->send();
    }

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
        AvaliacaoPessoalResource::validateScoreData($data);
        $data['dias_na_casa'] = AvaliacaoPessoalResource::calculateDiasNaCasa($data['acolhido_id'] ?? null);
        $data['Total'] = AvaliacaoPessoalResource::calculateTotal($data);

        return $data;
    }

    protected function afterSave(): void
    {
        AvaliacaoPessoalResource::notifyUsersAboutEvaluation($this->getRecord());
    }
}
