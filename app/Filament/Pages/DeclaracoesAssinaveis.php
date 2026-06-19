<?php

namespace App\Filament\Pages;

use App\Models\Acolhido;
use App\Models\User;
use App\Support\PortalContext;
use App\Support\ShieldPermission;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class DeclaracoesAssinaveis extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \UnitEnum | null $navigationGroup = 'Declaracoes Assinaveis';

    protected static ?string $navigationLabel = 'Gerador de declaracoes';

    protected static ?string $title = 'Declaracoes assinaveis';

    protected static ?int $navigationSort = 1;

    protected static string | \BackedEnum | null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected string $view = 'filament.pages.declaracoes-assinaveis';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'declaracao' => 'leitura_ptc',
            'acolhido_id' => null,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('declaracao')
                    ->label('Declaracao')
                    ->options(self::declarationOptions())
                    ->default('leitura_ptc')
                    ->required()
                    ->live(),
                Select::make('acolhido_id')
                    ->label('Acolhido')
                    ->options(fn (): array => Acolhido::query()
                        ->orderBy('nome_completo_paciente')
                        ->pluck('nome_completo_paciente', 'id')
                        ->all())
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(fn (Get $get): bool => self::declarationRequiresAcolhido((string) $get('declaracao')))
                    ->hidden(fn (Get $get): bool => ! self::declarationRequiresAcolhido((string) $get('declaracao'))),
                Placeholder::make('orientacoes')
                    ->label('Como funciona')
                    ->content(fn (): HtmlString => new HtmlString(
                        'Escolha a declaracao, selecione o acolhido quando necessario e use o botao <strong>Baixar PDF</strong> no topo para gerar o documento pronto para assinatura.'
                    )),
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
                ->disabled(fn (): bool => $this->buildDeclarationPayload() === null)
                ->action(fn () => $this->downloadPdf()),
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

    public function getPreviewPayload(): ?array
    {
        return $this->buildDeclarationPayload();
    }

    public function downloadPdf()
    {
        $payload = $this->buildDeclarationPayload();

        if ($payload === null) {
            return null;
        }

        $pdf = Pdf::loadView('pdf.declaracoes-assinaveis', [
            'payload' => $payload,
        ])->setPaper('a4');

        return response()->streamDownload(
            function () use ($pdf): void {
                echo $pdf->output();
            },
            self::pdfFileName($payload),
            ['Content-Type' => 'application/pdf'],
        );
    }

    /**
     * @return array<string, string>
     */
    private static function declarationOptions(): array
    {
        return [
            'leitura_ptc' => 'Declaracao de leitura do PTC',
            'termo_desligamento' => 'Declaracao de termo de desligamento',
            'uso_imagem' => 'Declaracao para uso de imagem',
            'desistencia_ptc' => 'Declaracao de desistencia do PTC',
            'acolhimento_voluntario' => 'Declaracao de acolhimento voluntario',
            'contrato_prevencao_recaida' => 'Contrato terapeutico - prevencao a recaida',
        ];
    }

    private static function declarationRequiresAcolhido(string $type): bool
    {
        return $type !== 'leitura_ptc';
    }

    /**
     * @return array<string, mixed>|null
     */
    private function buildDeclarationPayload(): ?array
    {
        $type = (string) ($this->data['declaracao'] ?? '');

        if ($type === '' || ! array_key_exists($type, self::declarationOptions())) {
            return null;
        }

        $acolhido = null;

        if (self::declarationRequiresAcolhido($type)) {
            $acolhidoId = $this->data['acolhido_id'] ?? null;

            if (! $acolhidoId) {
                return null;
            }

            $acolhido = Acolhido::query()->find($acolhidoId);

            if (! $acolhido instanceof Acolhido) {
                return null;
            }
        }

        $date = now();
        $dateParts = [
            'day' => $date->format('d'),
            'month' => self::monthName((int) $date->format('n')),
            'year' => $date->format('Y'),
        ];

        return [
            'type' => $type,
            'title' => self::documentTitle($type),
            'declarationLabel' => self::declarationOptions()[$type],
            'acolhido' => $acolhido,
            'dateParts' => $dateParts,
            'dateText' => "Luziânia, {$dateParts['day']} de {$dateParts['month']} de {$dateParts['year']}",
            'admissionDate' => $acolhido?->created_at?->format('d/m/Y') ?? '__/__/____',
            'addressLine' => self::buildAddressLine($acolhido),
            'cpf' => $acolhido?->numero_cpf ?: '_____________________',
            'rg' => $acolhido?->numero_rg ?: '_________________________________________',
            'signatureDateLine' => "_____________________, {$dateParts['day']} de {$dateParts['month']} de {$dateParts['year']}",
            'interventor' => self::buildInterventorPayload($acolhido),
            'acolhidoProfile' => self::buildAcolhidoProfilePayload($acolhido),
        ];
    }

    private static function documentTitle(string $type): string
    {
        return match ($type) {
            'leitura_ptc' => 'DECLARACAO DE LEITURA DO PTC',
            'termo_desligamento' => 'TERMO DE DESLIGAMENTO',
            'uso_imagem' => 'DECLARACAO PARA USO DE IMAGEM',
            'desistencia_ptc' => 'DECLARACAO DE DESISTENCIA DO PTC',
            'acolhimento_voluntario' => 'DECLARACAO DE ACOLHIMENTO VOLUNTARIO',
            'contrato_prevencao_recaida' => 'CONTRATO TERAPEUTICO - PREVENCAO A RECAIDA',
            default => 'DECLARACAO',
        };
    }

    private static function monthName(int $month): string
    {
        return [
            1 => 'janeiro',
            2 => 'fevereiro',
            3 => 'marco',
            4 => 'abril',
            5 => 'maio',
            6 => 'junho',
            7 => 'julho',
            8 => 'agosto',
            9 => 'setembro',
            10 => 'outubro',
            11 => 'novembro',
            12 => 'dezembro',
        ][$month] ?? '';
    }

    private static function buildAddressLine(?Acolhido $acolhido): string
    {
        if (! $acolhido instanceof Acolhido) {
            return '____________________________________________________________';
        }

        $segments = array_filter([
            $acolhido->endereco_paciente,
            $acolhido->bairro_do_paciente,
            $acolhido->municipio_do_paciente,
            $acolhido->uf_municipio_do_paciente,
            $acolhido->CEP ? 'CEP ' . $acolhido->CEP : null,
        ]);

        return $segments !== [] ? implode(', ', $segments) : '____________________________________________________________';
    }

    /**
     * @return array<string, string>
     */
    private static function buildInterventorPayload(?Acolhido $acolhido): array
    {
        return [
            'nome' => self::valueOrBlank($acolhido?->interventor_nome_completo, '________________________________________________________________'),
            'cpf' => self::valueOrBlank($acolhido?->interventor_cpf, '___________________'),
            'rg' => self::valueOrBlank($acolhido?->interventor_rg, '___________'),
            'exp' => self::valueOrBlank($acolhido?->interventor_exp, '___________'),
            'rgUf' => self::valueOrBlank($acolhido?->interventor_rg_uf, '______'),
            'profissao' => self::valueOrBlank($acolhido?->interventor_profissao, '______________'),
            'dataNascimento' => self::dateOrBlank($acolhido?->interventor_data_nascimento),
            'residente' => self::valueOrBlank($acolhido?->interventor_residente, '____________________________________________________________'),
            'complemento' => self::valueOrBlank($acolhido?->interventor_complemento, '_____________________'),
            'bairro' => self::valueOrBlank($acolhido?->interventor_bairro, '_________________________'),
            'cidade' => self::valueOrBlank($acolhido?->interventor_cidade, '_______________________________'),
            'uf' => self::valueOrBlank($acolhido?->interventor_endereco_uf, '______'),
            'telefone' => self::valueOrBlank($acolhido?->interventor_telefone_contato, '___________________________'),
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function buildAcolhidoProfilePayload(?Acolhido $acolhido): array
    {
        return [
            'nome' => self::valueOrBlank($acolhido?->nome_completo_paciente, '________________________________________________________'),
            'dataNascimento' => self::dateOrBlank($acolhido?->data_nascimento),
            'cpf' => self::valueOrBlank($acolhido?->numero_cpf, '___________________'),
            'rg' => self::valueOrBlank($acolhido?->numero_rg, '_______________________'),
            'profissao' => self::valueOrBlank($acolhido?->profissao, '_____________________________'),
            'naturalidade' => self::valueOrBlank($acolhido?->municipio_do_paciente, '___________________________________'),
            'uf' => self::valueOrBlank($acolhido?->uf_municipio_do_paciente, '______'),
            'nacionalidade' => self::valueOrBlank($acolhido?->user?->nacionalidade, '______________'),
        ];
    }

    private static function valueOrBlank(mixed $value, string $fallback): string
    {
        $text = trim((string) ($value ?? ''));

        return $text !== '' ? $text : $fallback;
    }

    private static function dateOrBlank(mixed $value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('d/m/Y');
        }

        $text = trim((string) ($value ?? ''));

        return $text !== '' ? $text : '__/__/____';
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private static function pdfFileName(array $payload): string
    {
        $name = $payload['acolhido'] instanceof Acolhido
            ? $payload['acolhido']->nome_completo_paciente
            : $payload['type'];

        return 'declaracao-' . Str::slug((string) $payload['type']) . '-' . Str::slug((string) $name) . '.pdf';
    }

}
