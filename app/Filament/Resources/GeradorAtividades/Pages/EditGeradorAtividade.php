<?php

namespace App\Filament\Resources\GeradorAtividades\Pages;

use App\Filament\Resources\GeradorAtividades\Concerns\PersistsGeradorAtividadeFormDraft;
use App\Filament\Resources\GeradorAtividades\GeradorAtividadeResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditGeradorAtividade extends EditRecord
{
    use PersistsGeradorAtividadeFormDraft;

    protected static string $resource = GeradorAtividadeResource::class;

    protected Width | string | null $maxContentWidth = Width::SevenExtraLarge;

    public function mount(int | string $record): void
    {
        parent::mount($record);

        $this->restoreGeradorAtividadeDraft();
    }

    public function getTitle(): string
    {
        return 'Editar quadro semanal de atividades';
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        return GeradorAtividadeResource::mutateDataBeforeFill($data);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return GeradorAtividadeResource::prepareFormData($data);
    }

    protected function afterSave(): void
    {
        GeradorAtividadeResource::syncAtividadesAcolhidos($this->getRecord());
        $this->forgetGeradorAtividadeDraft();
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Finalizar atividades');
    }

    protected function getGeradorAtividadeDraftSessionKey(): string
    {
        return 'gerador-atividades.edit.draft.' . (auth()->id() ?? 'guest') . '.' . $this->getRecord()->getKey();
    }
}
