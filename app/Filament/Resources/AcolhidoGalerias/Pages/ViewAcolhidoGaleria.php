<?php

namespace App\Filament\Resources\AcolhidoGalerias\Pages;

use App\Filament\Resources\AcolhidoGalerias\AcolhidoGaleriaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewAcolhidoGaleria extends ViewRecord
{
    protected static string $resource = AcolhidoGaleriaResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'Album de imagens';
    }

    public function getSubheading(): string | Htmlable | null
    {
        $record = $this->getRecord();

        return $record->acolhido?->nome_completo_paciente;
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Gerenciar album'),
        ];
    }
}
