<?php

namespace App\Filament\Resources\AvaliacaoPessoals\Pages;

use App\Filament\Resources\AvaliacaoPessoals\AvaliacaoPessoalResource;
use App\Filament\Widgets\AvaliacaoPessoalLineChart;
use App\Support\PortalContext;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Str;

class ListAvaliacaoPessoals extends ListRecords
{
    protected static string $resource = AvaliacaoPessoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('autoAvaliacao')
                ->label('Auto Avaliacao')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->url(AvaliacaoPessoalResource::getUrl('auto-avaliacao')),
            Action::make('downloadConsolidatedReport')
                ->label('Relatorio PDF geral')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->hidden(fn (): bool => PortalContext::isFamilyUser())
                ->action(function () {
                    $payload = AvaliacaoPessoalResource::getConsolidatedSummaryReportData();

                    if ($payload['rows']->isEmpty()) {
                        Notification::make()
                            ->title('Nenhuma avaliacao encontrada')
                            ->body('Nao existem avaliacoes registradas para gerar o relatorio geral.')
                            ->warning()
                            ->send();

                        return null;
                    }

                    $pdf = Pdf::loadView('pdf.avaliacao-pessoal-consolidado-report', $payload)
                        ->setPaper('a4');

                    $fileName = 'relatorio-geral-avaliacoes-acolhidos-' . Str::slug(now()->format('d-m-Y-H-i')) . '.pdf';

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        $fileName,
                        ['Content-Type' => 'application/pdf'],
                    );
                }),
            CreateAction::make()
                ->label('Nova avaliacao'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AvaliacaoPessoalLineChart::class,
        ];
    }
}
