<?php

namespace App\Filament\Resources\ProntuariosEvolucao\Pages;

use App\Filament\Resources\ProntuariosEvolucao\ProntuarioEvolucaoResource;
use App\Support\PortalContext;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

class ViewProntuarioEvolucao extends ViewRecord
{
    protected static string $resource = ProntuarioEvolucaoResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'Prontuario de evolucao';
    }

    public function getSubheading(): string | Htmlable | null
    {
        $record = $this->getRecord();

        return trim(implode(' • ', array_filter([
            $record->acolhido?->nome_completo_paciente,
            $record->data_prontuario?->format('d/m/Y H:i'),
        ])));
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadRelatorio')
                ->label('Baixar relatorio')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->hidden(fn (): bool => PortalContext::isFamilyUser())
                ->action(function () {
                    $record = $this->getRecord();

                    $pdf = Pdf::loadView('pdf.prontuario-evolucao-report', ProntuarioEvolucaoResource::getReportData($record))
                        ->setPaper('a4');

                    $fileName = 'relatorio-prontuario-evolucao-' . Str::slug($record->acolhido?->nome_completo_paciente ?? 'acolhido') . '.pdf';

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        $fileName,
                        ['Content-Type' => 'application/pdf'],
                    );
                }),
            EditAction::make(),
        ];
    }
}
