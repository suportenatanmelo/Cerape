<?php

namespace App\Filament\Pages;

use App\Models\Acolhido;
use App\Models\AtividadeDesenvolvida;
use App\Models\DemandaAcolhido;
use App\Models\Saude;
use App\Models\SubstanciaPsicoativas;
use App\Support\PdfImage;
use App\Support\PortalContext;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class Pia extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'PIA';

    protected static ?string $title = 'PLANO INDIVIDUAL DE ACOLHIMENTO';

    protected static ?int $navigationSort = 7;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected string $view = 'filament.pages.pia';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'acolhido_id' => null,
            'modules' => array_keys(self::moduleOptions()),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('acolhido_id')
                    ->label('Acolhido')
                    ->options(fn (): array => Acolhido::query()
                        ->orderBy('nome_completo_paciente')
                        ->pluck('nome_completo_paciente', 'id')
                        ->all())
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live(),
                CheckboxList::make('modules')
                    ->label('Módulos para impressão')
                    ->options(self::moduleOptions())
                    ->columns(1)
                    ->required()
                    ->helperText('Marque os módulos que deseja incluir no PDF do Plano Individual de Acolhimento.'),
                Placeholder::make('orientacoes')
                    ->hiddenLabel()
                    ->content(fn (): HtmlString => new HtmlString(
                        'Selecione um acolhido, marque os módulos desejados e use o botão <strong>Baixar PDF</strong> no topo para gerar o documento.'
                    )),
            ])
            ->statePath('data');
    }

    public function getTitle(): string | Htmlable
    {
        return 'PLANO INDIVIDUAL DE ACOLHIMENTO';
    }

    public function getPreviewPayload(): ?array
    {
        return $this->buildReportPayload();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadPdf')
                ->label('Baixar PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->disabled(fn (): bool => $this->buildReportPayload() === null)
                ->action(fn () => $this->downloadPdf()),
        ];
    }

    public static function canAccess(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function getNavigationGroup(): string | \UnitEnum | null
    {
        return PortalContext::portalNavigationGroup();
    }

    public function downloadPdf()
    {
        $payload = $this->buildReportPayload();

        if ($payload === null) {
            return null;
        }

        $pdf = Pdf::loadView('pdf.pia-report', $payload)
            ->setPaper('a4');

        $fileName = 'plano-individual-acolhimento-' . Str::slug($payload['acolhido']->nome_completo_paciente ?? 'acolhido') . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $fileName,
            ['Content-Type' => 'application/pdf'],
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    private function buildReportPayload(): ?array
    {
        $acolhidoId = $this->data['acolhido_id'] ?? null;
        $modules = collect($this->data['modules'] ?? [])
            ->filter(fn (mixed $module): bool => is_string($module) && array_key_exists($module, self::moduleOptions()))
            ->values()
            ->all();

        if (! $acolhidoId || empty($modules)) {
            return null;
        }

        $acolhido = Acolhido::query()->with('user')->find($acolhidoId);

        if (! $acolhido instanceof Acolhido) {
            return null;
        }

        $sections = [];

        foreach ($modules as $module) {
            $sections[$module] = match ($module) {
                'acolhidos' => $this->buildAcolhidosSection($acolhido),
                'substancia_psicoativas' => $this->buildSubstanciasSection($acolhido),
                'saude' => $this->buildSaudeSection($acolhido),
                'atividades_crc' => $this->buildAtividadesSection($acolhido),
                'demanda' => $this->buildDemandaSection($acolhido),
                default => [],
            };
        }

        return [
            'title' => 'PLANO INDIVIDUAL DE ACOLHIMENTO',
            'subtitle' => $acolhido->nome_completo_paciente,
            'metaLines' => [
                'Emitido em: ' . now()->format('d/m/Y H:i'),
                'Responsável pelo cadastro: ' . ($acolhido->user?->name ?? '-'),
                'Módulos selecionados: ' . implode(', ', array_map(fn (string $module): string => self::moduleOptions()[$module], $modules)),
            ],
            'highlight' => count($modules) === count(self::moduleOptions())
                ? 'PLANO COMPLETO'
                : 'PLANO PARCIAL',
            'photoData' => PdfImage::storageDataUri($acolhido->avatar),
            'photoLabel' => 'PIA',
            'logoCerape' => PdfImage::publicDataUri('storage/images/logo.png'),
            'sections' => $sections,
            'formatValue' => fn (mixed $value): string => self::formatValue($value),
            'acolhido' => $acolhido,
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function moduleOptions(): array
    {
        return [
            'acolhidos' => 'Acolhidos',
            'substancia_psicoativas' => 'Substâncias psicoativas',
            'saude' => 'Saúde',
            'atividades_crc' => 'Atividades CRC',
            'demanda' => 'Demanda',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildAcolhidosSection(Acolhido $acolhido): array
    {
        return [
            'Status' => $acolhido->ativo ? 'Ativo' : 'Desativado',
            'Nome completo' => $acolhido->nome_completo_paciente,
            'Data de nascimento' => $acolhido->data_nascimento,
            'CPF' => $acolhido->numero_cpf,
            'RG' => $acolhido->numero_rg,
            'Telefone' => $acolhido->numero_do_telefone,
            'Município' => $acolhido->municipio_do_paciente,
            'UF' => $acolhido->uf_municipio_do_paciente,
            'Escolaridade' => $acolhido->escolaridade,
            'Profissão' => $acolhido->profissao,
            'Cadastro em' => $acolhido->created_at,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildSubstanciasSection(Acolhido $acolhido): array
    {
        $record = SubstanciaPsicoativas::query()
            ->where('acolhido_id', $acolhido->id)
            ->latest()
            ->first();

        if (! $record instanceof SubstanciaPsicoativas) {
            return [
                'Situação' => 'Nenhum registro de substâncias psicoativas encontrado.',
            ];
        }

        return [
            'Substâncias' => $record->nome,
            'Frequência' => $record->frequencia,
            'Quantidade' => $record->quantidade,
            'Via de administração' => $record->via_administracao,
            'Tempo de uso' => $record->tempo_uso,
            'Última vez' => $record->ultima_vez,
            'Observações' => $record->observacoes,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildSaudeSection(Acolhido $acolhido): array
    {
        $record = Saude::query()
            ->where('acolhido_id', $acolhido->id)
            ->latest()
            ->first();

        if (! $record instanceof Saude) {
            return [
                'Situação' => 'Nenhum registro de saúde encontrado.',
            ];
        }

        return [
            'Faz tratamento médico' => $record->faz_tratamento_medico,
            'Condições de saúde' => $record->condicoes_saude,
            'Usa medicação psicoativa' => $record->usa_medicacao_psicoativa,
            'Medicação psicoativa' => $record->nome_medicacao_psicoativa,
            'Dosagem' => $record->dosagem_medicacao_psicoativa,
            'Prescrição profissional' => $record->prescrito_profissional,
            'Diagnósticos relacionados' => $record->diagnosticado,
            'Medicamentos em uso' => $record->medicamentos_em_uso,
            'Alergias ou restrições' => $record->alergias_restricoes,
            'Observações clínicas' => $record->observacoes_clinicas,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildAtividadesSection(Acolhido $acolhido): array
    {
        $record = AtividadeDesenvolvida::query()
            ->where('acolhido_id', $acolhido->id)
            ->latest()
            ->first();

        if (! $record instanceof AtividadeDesenvolvida) {
            return [
                'Situação' => 'Nenhum registro de atividades CRC encontrado.',
            ];
        }

        return [
            'Atendimento grupo 12 passos' => $record->atendimento_grupo_12_passos,
            'Horário grupo 12 passos' => $record->horario_atendimento_grupo_12_passos,
            'Atendimentos em grupos' => $record->atendimentos_grupos,
            'Horário grupos' => $record->horario_atendimentos_grupos,
            'Atendimentos individuais' => $record->atendimentos_individuais_conselheiros,
            'Horário atendimentos individuais' => $record->horario_atendimentos_individuais_conselheiros,
            'Conhecimento sobre dependência de SPA' => $record->conhecimento_dependencia_spa,
            'Atendimento à família' => $record->atendimento_familia,
            'Detalhes do atendimento à família' => $record->detalhes_atendimento_familia,
            'Atividades esportivas' => $record->atividades_esportivas,
            'Atividades lúdicas, culturais e musicais' => $record->atividades_ludicas_culturais_musicais,
            'Atividades práticas inclusivas' => $record->detalhes_atividades_praticas_inclusivas,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildDemandaSection(Acolhido $acolhido): array
    {
        $record = DemandaAcolhido::query()
            ->where('acolhido_id', $acolhido->id)
            ->latest()
            ->first();

        if (! $record instanceof DemandaAcolhido) {
            return [
                'Situação' => 'Nenhum registro de demanda encontrado.',
            ];
        }

        return [
            'Demanda' => $record->demanda,
            'Saída prevista' => $record->saida_prevista_em,
            'Retorno previsto' => $record->retorno_previsto_em,
            'Observações' => $record->observacoes,
            'Criado em' => $record->created_at,
            'Atualizado em' => $record->updated_at,
        ];
    }

    private static function formatValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'Sim' : 'Não';
        }

        if ($value instanceof \Carbon\CarbonInterface) {
            return $value->format($value->format('H:i:s') === '00:00:00' ? 'd/m/Y' : 'd/m/Y H:i');
        }

        if (is_array($value)) {
            return blank($value) ? '-' : implode(', ', array_filter($value));
        }

        $value = trim(strip_tags((string) $value));

        return $value !== '' ? $value : '-';
    }
}
