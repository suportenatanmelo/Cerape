<?php

namespace App\Filament\Resources\ProntuariosEvolucao\Pages;

use App\Filament\Resources\ProntuariosEvolucao\ProntuarioEvolucaoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditProntuarioEvolucao extends EditRecord
{
    protected static string $resource = ProntuarioEvolucaoResource::class;

    protected Width | string | null $maxContentWidth = Width::SevenExtraLarge;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->after(fn () => ProntuarioEvolucaoResource::notifyUsers($this->getRecord(), 'deleted')),
        ];
    }

    public function getTitle(): string
    {
        return 'Editar prontuario de evolucao';
    }

    protected function afterSave(): void
    {
        ProntuarioEvolucaoResource::notifyUsers($this->getRecord(), 'updated');
    }
}
