<?php

namespace App\Filament\Resources\Acolhidos\Pages;

use App\Filament\Resources\Acolhidos\Concerns\PersistsAcolhidoFormDraft;
use App\Filament\Resources\Acolhidos\AcolhidoResource;
use App\Filament\Resources\Acolhidos\Schemas\AcolhidoForm;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditAcolhido extends EditRecord
{
    use PersistsAcolhidoFormDraft;

    protected static string $resource = AcolhidoResource::class;

    protected Width | string | null $maxContentWidth = Width::Full;

    public function mount(int | string $record): void
    {
        parent::mount($record);

        $this->restoreAcolhidoDraft();
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->after(fn () => AcolhidoForm::notifyUsers($this->getRecord(), 'deleted'))
                ->successNotificationTitle('Acolhido excluido com sucesso'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Acolhido atualizado com sucesso';
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return AcolhidoForm::prepareForPersistence($data);
    }

    protected function afterSave(): void
    {
        $this->forgetAcolhidoDraft();
        AcolhidoForm::notifyUsers($this->getRecord(), 'updated');
    }

    protected function getAcolhidoDraftSessionKey(): string
    {
        return 'acolhidos.edit.draft.' . (auth()->id() ?? 'guest') . '.' . $this->getRecord()->getKey();
    }
}
