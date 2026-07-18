<?php

namespace App\Filament\Pages;

use App\Models\Acolhido;
use App\Models\User;
use App\Support\PortalContext;
use App\Support\ShieldPermission;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Schema as SchemaFacade;
use Illuminate\Support\Str;

class AcolhidosChecklist extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \UnitEnum | null $navigationGroup = 'Documentos e Relatórios';

    protected static ?string $navigationLabel = 'Gerador de acolhidos';

    protected static ?string $title = 'Gerador de acolhidos';

    protected static ?int $navigationSort = 98;

    protected static string | \BackedEnum | null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected string $view = 'filament.pages.acolhidos-checklist';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'acolhido_id' => null,
            'select_all_acolhidos' => false,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Toggle::make('select_all_acolhidos')
                    ->label('Selecionar todos os acolhidos')
                    ->live()
                    ->default(false)
                    ->afterStateUpdated(function (Set $set, ?bool $state): void {
                        if ($state) {
                            $set('acolhido_id', null);
                        }
                    }),
                Select::make('acolhido_id')
                    ->label('Acolhido')
                    ->options(fn (): array => Acolhido::query()
                        ->orderBy('nome_completo_paciente')
                        ->pluck('nome_completo_paciente', 'id')
                        ->all())
                    ->searchable()
                    ->preload()
                    ->required(fn (Get $get): bool => ! $get('select_all_acolhidos'))
                    ->hidden(fn (Get $get): bool => $get('select_all_acolhidos'))
                    ->live(),
                Placeholder::make('description')
                    ->label('O que faz este gerador')
                    ->content('Use o botão Baixar PDF para escolher as colunas que serão exibidas no relatório. Os campos Nome completo, CPF, Data de nascimento e Data do acolhimento são os padrões.'),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadPdf')
                ->label('Baixar PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->disabled(fn (): bool => empty($this->data['acolhido_id']) && ! ($this->data['select_all_acolhidos'] ?? false))
                ->form([
                    Placeholder::make('fields_info')
                        ->hiddenLabel()
                        ->content('Marque os campos que devem aparecer no PDF. Os campos Nome completo, CPF, Data de nascimento e Data do acolhimento vêm selecionados por padrão.'),
                    Toggle::make('select_all_columns')
                        ->label('Marcar todas as colunas')
                        ->live()
                        ->default(false)
                        ->afterStateUpdated(function (Set $set, ?bool $state): void {
                            $set('selected_columns', $state ? array_keys(self::columnOptions()) : self::defaultSelectedColumns());
                        }),
                    CheckboxList::make('selected_columns')
                        ->label('Colunas do checklist')
                        ->options(self::columnOptions())
                        ->default(self::defaultSelectedColumns())
                        ->columns(2)
                        ->required()
                        ->helperText('Selecione as colunas que deverão ser incluídas no PDF.'),
                ])
                ->action(function (array $data) {
                    $selectedColumns = array_values(array_filter($data['selected_columns'] ?? [], fn (mixed $value): bool => is_string($value) && $value !== ''));

                    if ($selectedColumns === []) {
                        return null;
                    }

                    $acolhidos = $this->getSelectedAcolhidos($this->data['select_all_acolhidos'] ?? false, $this->data['acolhido_id'] ?? null);

                    if ($acolhidos->isEmpty()) {
                        return null;
                    }

                    $pdf = Pdf::loadView('pdf.acolhidos-checklist', [
                        'acolhidos' => $acolhidos,
                        'acolhido' => $acolhidos->first(),
                        'selectedColumns' => $selectedColumns,
                        'columnLabels' => self::columnOptions(),
                    ])->setPaper('a4');

                    $slug = $this->data['select_all_acolhidos'] ? 'todos-acolhidos' : Str::slug($acolhidos->first()->nome_completo_paciente ?? 'acolhido');
                    $fileName = 'checklist-acolhidos-' . $slug . '-' . now()->format('Y-m-d') . '.pdf';

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        $fileName,
                        ['Content-Type' => 'application/pdf'],
                    );
                }),
        ];
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User
            && ! PortalContext::isFamilyUser($user)
            && ShieldPermission::allows($user, 'viewAny', 'Acolhido');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function getNavigationGroup(): string | \UnitEnum | null
    {
        return PortalContext::documentsNavigationGroup();
    }

    private function getSelectedAcolhidos(bool $selectAll, ?int $acolhidoId)
    {
        if ($selectAll) {
            return Acolhido::query()
                ->orderBy('nome_completo_paciente')
                ->get();
        }

        $acolhido = Acolhido::query()->find($acolhidoId);

        return $acolhido instanceof Acolhido ? collect([$acolhido]) : collect();
    }

    /**
     * @return array<string, string>
     */
    private static function columnOptions(): array
    {
        try {
            $columns = SchemaFacade::getColumnListing('acolhidos');
        } catch (\Throwable $e) {
            // Se o banco nao estiver disponivel, retorna conjunto vazio para evitar erro 500
            return [];
        }

        return collect($columns)
            ->mapWithKeys(fn (string $column): array => [$column => self::formatColumnLabel($column)])
            ->all();
    }

    /**
     * @return string[]
     */
    private static function defaultSelectedColumns(): array
    {
        return [
            'nome_completo_paciente',
            'numero_cpf',
            'data_nascimento',
            'created_at',
        ];
    }

    private static function formatColumnLabel(string $column): string
    {
        $map = [
            'nome_completo_paciente' => 'Nome completo',
            'numero_cpf' => 'CPF',
            'data_nascimento' => 'Data de nascimento',
            'created_at' => 'Data do acolhimento',
            'numero_rg' => 'RG',
            'numero_certidao_nascimento' => 'Certidão de nascimento',
            'numero_certidao_casamento' => 'Certidão de casamento',
            'numero_carteira_trabalho' => 'Carteira de trabalho',
            'numero_titulo_eleitor' => 'Título de eleitor',
            'numero_nis' => 'NIS',
            'numero_cartao_sus' => 'Cartão SUS',
            'CEP' => 'CEP',
            'endereco_paciente' => 'Endereço',
            'bairro_do_paciente' => 'Bairro',
            'municipio_do_paciente' => 'Município',
            'uf_municipio_do_paciente' => 'UF',
            'estado_civil' => 'Estado civil',
            'nome_do_conjuge' => 'Nome do cônjuge',
            'nome_da_mae' => 'Nome da mãe',
            'nome_do_pai' => 'Nome do pai',
            'tem_documentacao' => 'Tem documentação',
            'documentos_civis' => 'Documentos civis',
            'documentos_outros' => 'Documentos outros',
            'cor_da_pele' => 'Cor da pele',
            'escolaridade' => 'Escolaridade',
            'profissao' => 'Profissão',
            'religiao' => 'Religião',
            'tem_telefone' => 'Tem telefone',
            'numero_do_telefone' => 'Telefone',
            'tem_meio_de_encaminhamento' => 'Tem meio de encaminhamento',
            'meio_de_encaminhamento' => 'Meio de encaminhamento',
            'outro_meio_de_encaminhamento_qual' => 'Outro meio de encaminhamento',
            'indicacao' => 'Indicação',
            'toma_medicamento' => 'Toma medicamento',
            'qual_sao_as_medicacao' => 'Quais medicamentos',
            'tem_receituario' => 'Tem receituário',
            'receituario' => 'Receituário',
            'exames_laboratoriais' => 'Exames laboratoriais',
            'outros' => 'Outros',
            'tem_filhos' => 'Tem filhos',
            'quantidade_filhos' => 'Quantidade de filhos',
            'nome_da_empresa_que_trabalha' => 'Empresa onde trabalha',
            'profissional_referencia_acolhido_instituicao' => 'Profissional de referência / instituição',
        ];

        return $map[$column] ?? Str::of($column)
            ->replace('_', ' ')
            ->title()
            ->replace('Cpf', 'CPF')
            ->replace('Rg', 'RG')
            ->replace('Cep', 'CEP')
            ->toString();
    }
}
