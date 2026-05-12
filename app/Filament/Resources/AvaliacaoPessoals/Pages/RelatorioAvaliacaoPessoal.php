<?php

namespace App\Filament\Resources\AvaliacaoPessoals\Pages;

use App\Filament\Resources\AvaliacaoPessoals\AvaliacaoPessoalResource;
use App\Filament\Widgets\AvaliacaoPessoalAcolhidoChart;
use App\Filament\Widgets\AvaliacaoPessoalPeriodoComparativoChart;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

class RelatorioAvaliacaoPessoal extends Page
{
    use InteractsWithRecord;

    protected static string $resource = AvaliacaoPessoalResource::class;

    protected string $view = 'filament.resources.avaliacao-pessoals.pages.relatorio-avaliacao-pessoal';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getTitle(): string | Htmlable
    {
        return 'Relatorio detalhado da avaliacao pessoal';
    }

    public function getBreadcrumb(): string
    {
        return 'Relatorio detalhado';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadRelatorio')
                ->label('Baixar PDF')
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
            Action::make('voltar')
                ->label('Voltar para avaliacao')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn (): string => AvaliacaoPessoalResource::getUrl('view', ['record' => $this->getRecord()])),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AvaliacaoPessoalPeriodoComparativoChart::class,
            AvaliacaoPessoalAcolhidoChart::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }

    protected function getViewData(): array
    {
        return AvaliacaoPessoalResource::getReportData($this->getRecord());
    }
}
