<?php

namespace App\Filament\Resources\GeradorAtividades\Pages;

use App\Filament\Resources\GeradorAtividades\GeradorAtividadeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditGeradorAtividade extends EditRecord
{
    protected static string $resource = GeradorAtividadeResource::class;

    protected Width | string | null $maxContentWidth = Width::SevenExtraLarge;

    public function getTitle(): string
    {
        return 'Editar programacao de atividades';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
