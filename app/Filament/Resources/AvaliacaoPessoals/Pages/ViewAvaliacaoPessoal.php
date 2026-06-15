<?php

namespace App\Filament\Resources\AvaliacaoPessoals\Pages;

use App\Filament\Resources\AvaliacaoPessoals\AvaliacaoPessoalResource;
use App\Filament\Widgets\AvaliacaoPessoalAcolhidoChart;
use App\Support\PortalContext;
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

    public function getSubheading(): string | Htmlable | null
    {
        $record = $this->getRecord();

        return trim(implode(' • ', array_filter([
            $record->acolhido?->nome_completo_paciente,
            $record->user?->name ? 'Registrada por ' . $record->user->name : null,
        ])));
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('visualizarRelatorio')
                ->label('Visualizar relatorio')
                ->icon('heroicon-o-document-chart-bar')
                ->color('primary')
                ->url(fn (): string => AvaliacaoPessoalResource::getUrl('report', ['record' => $this->getRecord()])),
            Action::make('downloadRelatorio')
                ->label('Baixar relatorio')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->hidden(fn (): bool => PortalContext::isFamilyUser())
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
