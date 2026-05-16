<?php

namespace App\Filament\Resources\Reunioes\Pages;

use App\Filament\Resources\Reunioes\ReuniaoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditReuniao extends EditRecord
{
    protected static string $resource = ReuniaoResource::class;

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
        $data['user_id'] ??= $this->getRecord()->user_id ?? auth()->id();

        return $data;
    }
}
