<?php

namespace App\Filament\Resources\ProntuariosEvolucao\Pages;

use App\Filament\Resources\ProntuariosEvolucao\ProntuarioEvolucaoResource;
use App\Models\AtividadeAcolhido;
use App\Models\ProntuarioEvolucao;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use Filament\Support\Enums\Width;

class CreateProntuarioEvolucao extends CreateRecord
{
    protected static string $resource = ProntuarioEvolucaoResource::class;

    protected Width | string | null $maxContentWidth = Width::SevenExtraLarge;

    public function getTitle(): string
    {
        return 'Novo prontuario de evolucao';
    }

    protected function afterCreate(): void
    {
        $record = $this->getRecord();

        if ($record->atividade_gerada_id) {
            $atividade = AtividadeAcolhido::query()->find($record->atividade_gerada_id);
            $jaEvoluida = ProntuarioEvolucao::query()
                ->where('atividade_gerada_id', $record->atividade_gerada_id)
                ->whereKeyNot($record->getKey())
                ->exists();

            if ($atividade && ($atividade->status === 'evoluida' || $jaEvoluida)) {
                $record->delete();

                Notification::make()
                    ->danger()
                    ->title('Esta atividade já possui uma evolução cadastrada.')
                    ->send();

                throw ValidationException::withMessages([
                    'atividade_gerada_id' => 'Esta atividade já possui uma evolução cadastrada.',
                ]);
            }

            $atividade?->forceFill([
                'status' => 'evoluida',
            ])->save();
        }

        ProntuarioEvolucaoResource::notifyUsers($this->getRecord(), 'created');
    }
}
