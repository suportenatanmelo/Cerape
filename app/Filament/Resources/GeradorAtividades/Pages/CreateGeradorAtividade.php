<?php

namespace App\Filament\Resources\GeradorAtividades\Pages;

use App\Filament\Resources\GeradorAtividades\Concerns\PersistsGeradorAtividadeFormDraft;
use App\Filament\Resources\GeradorAtividades\GeradorAtividadeResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateGeradorAtividade extends CreateRecord
{
    use PersistsGeradorAtividadeFormDraft;

    protected static string $resource = GeradorAtividadeResource::class;

    protected static bool $canCreateAnother = false;

    protected Width | string | null $maxContentWidth = Width::SevenExtraLarge;

    public function mount(): void
    {
        parent::mount();

        $this->restoreGeradorAtividadeDraft();
    }

    public function getTitle(): string
    {
        return 'Novo quadro semanal de atividades';
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return GeradorAtividadeResource::prepareFormData($data);
    }

    protected function afterCreate(): void
    {
        $this->forgetGeradorAtividadeDraft();
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Finalizar atividades');
    }

    protected function getGeradorAtividadeDraftSessionKey(): string
    {
        return 'gerador-atividades.create.draft.' . (auth()->id() ?? 'guest');
    }
}
