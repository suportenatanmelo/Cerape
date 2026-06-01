<?php

namespace App\Filament\Resources\Saudes\Pages;

use App\Filament\Resources\Saudes\SaudeResource;
use App\Support\PortalContext;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewSaude extends ViewRecord
{
    protected static string $resource = SaudeResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'Ficha de saude';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return $this->getRecord()->acolhido?->nome_completo_paciente;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadRelatorio')
                ->label('Baixar relatorio')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->hidden(fn (): bool => PortalContext::isFamilyUser())
                ->action(fn () => SaudeResource::downloadReportResponse($this->getRecord())),
            EditAction::make(),
        ];
    }
}
