<?php

namespace App\Filament\Resources\Reunioes\Pages;

use App\Filament\Resources\Reunioes\ReuniaoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReuniao extends CreateRecord
{
    protected static string $resource = ReuniaoResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
