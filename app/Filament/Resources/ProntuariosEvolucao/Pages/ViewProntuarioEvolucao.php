<?php

namespace App\Filament\Resources\ProntuariosEvolucao\Pages;

use App\Filament\Resources\ProntuariosEvolucao\ProntuarioEvolucaoResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Str;

class ViewProntuarioEvolucao extends ViewRecord
{
    protected static string $resource = ProntuarioEvolucaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadRelatorio')
                ->label('Baixar relatorio')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
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
