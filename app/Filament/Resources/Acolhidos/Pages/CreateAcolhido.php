<?php

namespace App\Filament\Resources\Acolhidos\Pages;

use App\Filament\Resources\Acolhidos\Concerns\PersistsAcolhidoFormDraft;
use App\Filament\Resources\Acolhidos\AcolhidoResource;
use App\Filament\Resources\Acolhidos\Schemas\AcolhidoForm;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateAcolhido extends CreateRecord
{
    use PersistsAcolhidoFormDraft;

    protected static string $resource = AcolhidoResource::class;

    protected Width | string | null $maxContentWidth = Width::Full;

    public function mount(): void
    {
        parent::mount();

        $this->restoreAcolhidoDraft();
    }

    public function getTitle(): string
    {
        return 'Criar Acolhido';
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Acolhido cadastrado com sucesso';
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return AcolhidoForm::prepareForPersistence($data);
    }

    protected function afterCreate(): void
    {
        $this->forgetAcolhidoDraft();
        AcolhidoForm::persistDemandaFromForm($this->getRecord(), $this->data);
        AcolhidoForm::notifyUsers($this->getRecord(), 'created');
    }

    protected function getAcolhidoDraftSessionKey(): string
    {
        return 'acolhidos.create.draft.' . (auth()->id() ?? 'guest');
    }
}
