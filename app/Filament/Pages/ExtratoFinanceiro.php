<?php

namespace App\Filament\Pages;

use App\Models\Acolhido;
use App\Models\EmpresaParceira;
use App\Models\User;
use App\Services\Financeiro\ExtratoFinanceiroService;
use App\Support\PortalContext;
use App\Support\ShieldPermission;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExtratoFinanceiro extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\UnitEnum|null $navigationGroup = 'Financeiro';

    protected static ?string $navigationLabel = 'Extratos';

    protected static ?int $navigationSort = 6;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-receipt-refund';

    protected string $view = 'filament.pages.extrato-financeiro';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'acolhido_id' => request()->integer('acolhido_id') ?: null,
            'data_inicial' => request()->string('data_inicial')->toString() ?: null,
            'data_final' => request()->string('data_final')->toString() ?: null,
            'empresa_id' => request()->integer('empresa_id') ?: null,
            'tipo_movimentacao' => request()->string('tipo_movimentacao')->toString() ?: null,
            'situacao' => request()->string('situacao')->toString() ?: null,
            'search' => null,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('acolhido_id')
                ->label('Acolhido')
                ->options(fn (): array => Acolhido::query()->orderBy('nome_completo_paciente')->pluck('nome_completo_paciente', 'id')->all())
                ->searchable()
                ->preload()
                ->live()
                ->helperText('Escolha o acolhido para atualizar o extrato automaticamente.'),
            DatePicker::make('data_inicial')->label('Data inicial')->live(),
            DatePicker::make('data_final')->label('Data final')->live(),
            Select::make('empresa_id')
                ->label('Empresa')
                ->options(fn (): array => EmpresaParceira::query()->orderBy('nome')->pluck('nome', 'id')->all())
                ->searchable()
                ->preload()
                ->live(),
            Select::make('tipo_movimentacao')
                ->label('Tipo de movimentação')
                ->options([
                    null => 'Todos',
                    'Crédito' => 'Crédito',
                    'Débito' => 'Débito',
                    'Diária' => 'Diária',
                    'Saque' => 'Saque',
                    'Compra Interna' => 'Compra Interna',
                    'Transferência Família' => 'Transferência Família',
                    'Ajuste' => 'Ajuste',
                    'Desconto' => 'Desconto',
                ])
                ->live(),
            Select::make('situacao')
                ->label('Situação')
                ->options([
                    null => 'Todos',
                    'confirmado' => 'Confirmado',
                    'pendente' => 'Pendente',
                    'cancelado' => 'Cancelado',
                ])
                ->live(),
        ])->statePath('data');
    }

    public function getTitle(): string|Htmlable
    {
        return 'Extrato Financeiro';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Consulta consolidada do histórico financeiro do acolhido.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('limpar')
                ->label('Limpar filtros')
                ->color('gray')
                ->action(fn (): mixed => $this->form->fill(['acolhido_id' => null, 'data_inicial' => null, 'data_final' => null, 'empresa_id' => null, 'tipo_movimentacao' => null, 'situacao' => null, 'search' => null])),
            Action::make('imprimir')
                ->label('Imprimir')
                ->color('info')
                ->icon('heroicon-o-printer')
                ->action(fn (): StreamedResponse => $this->downloadPdf(true)),
            Action::make('pdf')
                ->label('Gerar PDF')
                ->color('success')
                ->icon('heroicon-o-document-arrow-down')
                ->action(fn (): StreamedResponse => $this->downloadPdf(false)),
            Action::make('excel')
                ->label('Exportar Excel')
                ->color('warning')
                ->icon('heroicon-o-table-cells')
                ->action(fn (): StreamedResponse => $this->downloadCsv()),
        ];
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User
            && ! PortalContext::isFamilyUser($user)
            && ShieldPermission::allows($user, 'view', 'ExtratoFinanceiro');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return self::canAccess();
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return 'Financeiro';
    }

    public function getDataSet(): array
    {
        return app(ExtratoFinanceiroService::class)->build($this->data['acolhido_id'] ?? null, $this->filters());
    }

    /**
     * @return array<string, mixed>
     */
    public function filters(): array
    {
        return array_filter([
            'data_inicial' => $this->data['data_inicial'] ?? null,
            'data_final' => $this->data['data_final'] ?? null,
            'empresa_id' => $this->data['empresa_id'] ?? null,
            'tipo_movimentacao' => $this->data['tipo_movimentacao'] ?? null,
            'situacao' => $this->data['situacao'] ?? null,
            'search' => $this->data['search'] ?? null,
        ], fn (mixed $value): bool => filled($value));
    }

    public function downloadPdf(bool $print = false): StreamedResponse
    {
        $dataSet = $this->getDataSet();
        $acolhido = $dataSet['acolhido'];

        if (! $acolhido) {
            abort(422, 'Selecione um acolhido.');
        }

        $pdf = Pdf::loadView('pdf.extrato-financeiro', [
            'acolhido' => $acolhido,
            'summary' => $dataSet['summary'],
            'entries' => $dataSet['entries'],
            'filters' => $this->data,
            'printedAt' => now(),
            'printedBy' => auth()->user(),
        ])->setPaper('a4');

        if ($print) {
            $pdf->setOption('isHtml5ParserEnabled', true);
        }

        $name = 'extrato-financeiro-' . str()->slug($acolhido->nome_completo_paciente) . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $name,
            ['Content-Type' => 'application/pdf'],
        );
    }

    public function downloadCsv(): StreamedResponse
    {
        $dataSet = $this->getDataSet();
        $acolhido = $dataSet['acolhido'];

        if (! $acolhido) {
            abort(422, 'Selecione um acolhido.');
        }

        $fileName = 'extrato-financeiro-' . str()->slug($acolhido->nome_completo_paciente) . '.csv';

        return response()->streamDownload(function () use ($dataSet): void {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Data', 'Tipo', 'Descricao', 'Empresa', 'Credito', 'Debito', 'Saldo apos lancamento', 'Responsavel', 'Observacoes']);

            foreach ($dataSet['entries'] as $entry) {
                fputcsv($out, [
                    $entry->data->format('d/m/Y'),
                    $entry->tipo,
                    $entry->descricao,
                    $entry->empresa,
                    number_format($entry->credito, 2, ',', '.'),
                    number_format($entry->debito, 2, ',', '.'),
                    number_format($entry->saldoAposLancamento, 2, ',', '.'),
                    $entry->responsavel,
                    $entry->observacoes,
                ]);
            }

            fclose($out);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * @return Collection<int, \App\Data\Financeiro\ExtratoFinanceiroEntryData>
     */
    public function getEntriesProperty(): Collection
    {
        return $this->getDataSet()['entries'];
    }

    /**
     * @return array<string, mixed>
     */
    public function getSummaryProperty(): array
    {
        return $this->getDataSet()['summary'];
    }

    public function getAcolhidoProperty(): ?Acolhido
    {
        return $this->getDataSet()['acolhido'];
    }
}
