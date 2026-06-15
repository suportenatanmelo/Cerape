<?php

namespace App\Filament\Resources\AvaliacaoPessoals\Pages;

use App\Filament\Resources\AvaliacaoPessoals\AvaliacaoPessoalResource;
use App\Support\PortalContext;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;

class AutoAvaliacao extends Page
{
    protected static string $resource = AvaliacaoPessoalResource::class;

    protected string $view = 'filament.resources.avaliacao-pessoals.pages.auto-avaliacao';

    public function getTitle(): string
    {
        return 'Auto Avaliacao';
    }

    public function getBreadcrumb(): string
    {
        return 'Auto Avaliacao';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadRelatorio')
                ->label('Baixar PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->hidden(fn (): bool => PortalContext::isFamilyUser())
                ->action(function () {
                    $pdf = Pdf::loadView('pdf.auto-avaliacao-report', AvaliacaoPessoalResource::getAutoEvaluationReportData())
                        ->setPaper('a4', 'landscape');

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'auto-avaliacao-acolhidos.pdf',
                        ['Content-Type' => 'application/pdf'],
                    );
                }),
            Action::make('voltar')
                ->label('Voltar para avaliacoes')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn (): string => AvaliacaoPessoalResource::getUrl('index')),
        ];
    }

    protected function getViewData(): array
    {
        return AvaliacaoPessoalResource::getAutoEvaluationReportData();
    }
}
