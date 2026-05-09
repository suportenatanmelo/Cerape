<?php

namespace App\Filament\Resources\AvaliacaoPessoals\Pages;

use App\Filament\Resources\AvaliacaoPessoals\AvaliacaoPessoalResource;
use App\Filament\Widgets\AvaliacaoPessoalAcolhidoChart;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

class ViewAvaliacaoPessoal extends ViewRecord
{
    protected static string $resource = AvaliacaoPessoalResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'Avaliacao do acolhido';
    }

    public function getBreadcrumb(): string
    {
        return 'Avaliacao do acolhido';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadRelatorio')
                ->label('Baixar relatorio')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $record = $this->getRecord();
                    $record->loadMissing('acolhido');

                    $pdf = Pdf::loadView('pdf.avaliacao-pessoal-report', AvaliacaoPessoalResource::getReportData($record))
                        ->setPaper('a4');

                    $fileName = 'relatorio-avaliacao-' . Str::slug($record->acolhido?->nome_completo_paciente ?? 'acolhido') . '.pdf';

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        $fileName,
                        ['Content-Type' => 'application/pdf'],
                    );
                }),
            EditAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AvaliacaoPessoalAcolhidoChart::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }
}
