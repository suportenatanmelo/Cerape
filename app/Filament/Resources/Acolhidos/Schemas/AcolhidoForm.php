<?php

namespace App\Filament\Resources\Acolhidos\Schemas;


use App\Filament\Resources\Acolhidos\AcolhidoResource;
use App\Models\Acolhido;
use App\Models\User;
use App\Support\AcolhidoAccess;
use App\Support\FilamentDatabaseNotifications;
use Filament\Actions\Action;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Services\CorreiosCepService;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class AcolhidoForm
{
    public static function notifyUsers(Acolhido $acolhido, string $event): void
    {
        $users = AcolhidoAccess::notificationRecipientsForAcolhido((int) $acolhido->getKey());

        if ($users->isEmpty()) {
            return;
        }

        $notification = Notification::make()
            ->title(self::notificationTitle($event))
            ->body(self::notificationBody($acolhido, $event))
            ->icon(self::notificationIcon($event))
            ->viewData([
                'key' => self::notificationKey($acolhido, $event),
            ]);

        if (in_array($event, ['created', 'updated'], true)) {
            $notification->actions([
                Action::make('viewAcolhidoProfile')
                    ->label('Ver perfil')
                    ->button()
                    ->markAsRead()
                    ->url(self::notificationUrl($acolhido), shouldOpenInNewTab: true),
            ]);
        }

        match ($event) {
            'created' => $notification->success(),
            'deleted', 'forceDeleted' => $notification->danger(),
            'restored' => $notification->success(),
            default => $notification->info(),
        };

        FilamentDatabaseNotifications::send($notification, $users);
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Cadastro')
                        ->schema([
                            Section::make('Cadastro e identificacao')
                                ->schema([
                                    Grid::make([
                                        'default' => 1,
                                        'md' => 2,
                                    ])->schema([
                                        Select::make('user_id')
                                            ->label('Quem esta cadastrando?')
                                            ->relationship(
                                                'user',
                                                'name',
                                                modifyQueryUsing: fn(Builder $query): Builder => $query
                                                    ->whereNull('acolhido_id')
                                                    ->orderBy('name'),
                                            )
                                            ->searchable()
                                            ->preload()
                                            ->default(fn(): ?int => auth()->id())
                                            ->required(),
                                        TextInput::make('nome_completo_paciente')
                                            ->label('Nome completo do paciente')
                                            ->required(),
                                        DateTimePicker::make('created_at')
                                            ->label('Data de criacao do cadastro')
                                            ->seconds(false)
                                            ->visibleOn('edit'),
                                        Toggle::make('ativo')
                                            ->label('Cadastro ativo')
                                            ->default(true)
                                            ->onColor('success')
                                            ->offColor('danger')
                                            ->helperText('Desative quando o acolhido nao estiver mais em acompanhamento ativo.'),
                                        FileUpload::make('avatar')
                                            ->label('Foto do Acolhido')
                                            ->image()
                                            ->imageEditor()
                                            ->avatar()
                                            ->disk('public')
                                            ->directory('acolhidos/avatars')
                                            ->visibility('public')
                                            ->maxFiles(1)
                                            ->getUploadedFileNameForStorageUsing(
                                                fn(TemporaryUploadedFile $file): string =>
                                                uniqid() . '.' . $file->getClientOriginalExtension()
                                            ),
                                        DatePicker::make('data_nascimento')
                                            ->label('Data de nascimento')
                                            ->required(),
                                        Radio::make('estado_civil')
                                            ->label('Estado civil')
                                            ->options([
                                                'solteiro' => 'Solteiro',
                                                'casado' => 'Casado',
                                                'viuvo' => 'Viuvo',
                                            ])
                                            ->inline()
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, mixed $state): void {
                                                if (in_array((string) $state, ['solteiro', 'viuvo'], true)) {
                                                    $set('nome_do_conjuge', null);
                                                }
                                            }),
                                        TextInput::make('nome_do_conjuge')
                                            ->label('Nome do conjuge')
                                            ->hidden(fn(Get $get): bool => self::shouldHideNomeDoConjuge($get))
                                            ->dehydratedWhenHidden(),
                                        TextInput::make('nome_da_mae')
                                            ->label('Nome da mae')
                                            ->required(),
                                        TextInput::make('nome_do_pai')
                                            ->label('Nome do pai')
                                            ->required(),
                                        TextInput::make('cor_da_pele')
                                            ->label('Cor da pele')
                                            ->required(),
                                        Select::make('escolaridade')
                                            ->label('Escolaridade')
                                            ->options(self::getBrazilianEducationLevels())
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                        TextInput::make('escolaridade_observacao')
                                            ->label('Observacao da escolaridade')
                                            ->placeholder('Ex.: cursando, interrompido, incompleto, EJA, supletivo...')
                                            ->maxLength(255),
                                        TextInput::make('profissao')
                                            ->label('Profissao')
                                            ->required(),
                                        TextInput::make('religiao')
                                            ->label('Qual a religiao do acolhido?')
                                            ->placeholder('Informe a religiao do acolhido')
                                            ->required(),
                                    ]),
                                ]),
                        ]),
                    Step::make('Endereco')
                        ->schema([
                            Section::make('Endereco e moradia')
                                ->schema([
                                    Grid::make([
                                        'default' => 1,
                                        'md' => 2,
                                    ])->schema([
                                        TextInput::make('CEP')
                                            ->label('CEP')
                                            ->mask('99999-999')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Set $set, mixed $state): void {
                                                $address = app(CorreiosCepService::class)->lookup((string) $state);

                                                if (! is_array($address)) {
                                                    return;
                                                }

                                                $set('endereco_paciente', $address['endereco'] ?? null);
                                                $set('bairro_do_paciente', $address['bairro'] ?? null);
                                                $set('municipio_do_paciente', $address['municipio'] ?? null);
                                                $set('uf_municipio_do_paciente', $address['uf'] ?? null);
                                            }),
                                        TextInput::make('endereco_paciente')
                                            ->label('Endereco do paciente')
                                            ->required(),
                                        TextInput::make('bairro_do_paciente')
                                            ->label('Bairro do paciente')
                                            ->required(),
                                        TextInput::make('municipio_do_paciente')
                                            ->label('Municipio')
                                            ->required(),
                                        Select::make('uf_municipio_do_paciente')
                                            ->label('UF')
                                            ->options(self::getBrazilianStates())
                                            ->searchable()
                                            ->required(),
                                        Radio::make('moradia_propria')
                                            ->label('Moradia propria')
                                            ->boolean('Sim', 'Nao')
                                            ->inline()
                                            ->default(false)
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, mixed $state): void {
                                                if (! self::isYes($state)) {
                                                    return;
                                                }

                                                $set('mora_em_casa_aluguada', false);
                                                $set('quanto_tempo_de_aluguel', null);
                                                $set('em_qual_regiao', null);
                                            }),
                                        Radio::make('mora_em_casa_aluguada')
                                            ->label('Mora em casa alugada?')
                                            ->boolean('Sim', 'Nao')
                                            ->inline()
                                            ->default(false)
                                            ->live()
                                            ->hidden(fn(Get $get): bool => self::isYes($get('moradia_propria')))
                                            ->dehydratedWhenHidden()
                                            ->afterStateUpdated(function (Set $set, mixed $state): void {
                                                if (self::isYes($state)) {
                                                    return;
                                                }

                                                $set('quanto_tempo_de_aluguel', null);
                                                $set('em_qual_regiao', null);
                                            }),
                                        Select::make('quanto_tempo_de_aluguel')
                                            ->label('Quanto tempo de aluguel')
                                            ->options([
                                                '6 meses' => '6 meses',
                                                'Mais de 1 ano' => 'Mais de 1 ano',
                                            ])
                                            ->hidden(fn(Get $get): bool => self::shouldHideQuantoTempoDeAluguel($get))
                                            ->dehydratedWhenHidden(),
                                        TextInput::make('em_qual_regiao')
                                            ->label('Em qual regiao')
                                            ->hidden(fn(Get $get): bool => self::shouldHideQuantoTempoDeAluguel($get))
                                            ->dehydratedWhenHidden(),
                                    ]),
                                ]),
                            Section::make('Documentacao')
                                ->schema([
                                    Grid::make([
                                        'default' => 1,
                                        'md' => 2,
                                    ])->schema([
                                        Radio::make('tem_documentacao')
                                            ->label('Tem documentacao?')
                                            ->boolean('Sim', 'Nao')
                                            ->inline()
                                            ->default(false)
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, mixed $state): void {
                                                if (self::isYes($state)) {
                                                    $set('razao_caso_nao_tenha_documentacao', null);
                                                    return;
                                                }

                                                $set('documentos_civis', null);
                                                $set('documentos_outros', null);
                                                self::clearDocumentNumberFields($set);
                                            }),
                                        TextInput::make('razao_caso_nao_tenha_documentacao')
                                            ->label('Caso nao tenha documento')
                                            ->hidden(fn(Get $get): bool => self::isYes($get('tem_documentacao')))
                                            ->required(fn(Get $get): bool => ! self::isYes($get('tem_documentacao')))
                                            ->dehydratedWhenHidden(),
                                        CheckboxList::make('documentos_civis')
                                            ->label('Documentos civis')
                                            ->options([
                                                'rg' => 'RG',
                                                'cpf' => 'CPF',
                                                'certidao_nascimento' => 'Certidao de nascimento',
                                                'certidao_casamento' => 'Certidao de casamento',
                                                'carteira_trabalho' => 'Carteira de trabalho',
                                                'titulo_eleitor' => 'Titulo de eleitor',
                                            ])
                                            ->columns(2)
                                            ->columnSpanFull()
                                            ->live()
                                            ->afterStateUpdated(fn(Set $set, mixed $state): bool => self::syncDocumentNumberFields($set, $state, self::civilDocumentNumberFields()))
                                            ->hidden(fn(Get $get): bool => ! self::isYes($get('tem_documentacao')))
                                            ->dehydratedWhenHidden(),
                                        TextInput::make('numero_rg')
                                            ->label('Numero do RG')
                                            ->mask('99.999.999-9')
                                            ->maxLength(12)
                                            ->hidden(fn(Get $get): bool => ! self::hasSelectedDocument($get, 'documentos_civis', 'rg'))
                                            ->dehydratedWhenHidden(),
                                        TextInput::make('numero_cpf')
                                            ->label('Numero do CPF')
                                            ->mask('999.999.999-99')
                                            ->maxLength(14)
                                            ->hidden(fn(Get $get): bool => ! self::hasSelectedDocument($get, 'documentos_civis', 'cpf'))
                                            ->dehydratedWhenHidden(),
                                        TextInput::make('numero_certidao_nascimento')
                                            ->label('Numero da certidao de nascimento')
                                            ->hidden(fn(Get $get): bool => ! self::hasSelectedDocument($get, 'documentos_civis', 'certidao_nascimento'))
                                            ->dehydratedWhenHidden(),
                                        TextInput::make('numero_certidao_casamento')
                                            ->label('Numero da certidao de casamento')
                                            ->hidden(fn(Get $get): bool => ! self::hasSelectedDocument($get, 'documentos_civis', 'certidao_casamento'))
                                            ->dehydratedWhenHidden(),
                                        TextInput::make('numero_carteira_trabalho')
                                            ->label('Numero da carteira de trabalho')
                                            ->hidden(fn(Get $get): bool => ! self::hasSelectedDocument($get, 'documentos_civis', 'carteira_trabalho'))
                                            ->dehydratedWhenHidden(),
                                        TextInput::make('numero_titulo_eleitor')
                                            ->label('Numero do titulo de eleitor')
                                            ->mask('9999 9999 9999')
                                            ->maxLength(14)
                                            ->hidden(fn(Get $get): bool => ! self::hasSelectedDocument($get, 'documentos_civis', 'titulo_eleitor'))
                                            ->dehydratedWhenHidden(),
                                        CheckboxList::make('documentos_outros')
                                            ->label('Outros documentos')
                                            ->options([
                                                'comprovante_residencia' => 'Comprovante de residencia',
                                                'nis' => 'NIS/PIS',
                                                'cartao_sus' => 'Cartao do SUS',
                                                'certidao_antecedentes' => 'Certidao de antecedentes',
                                            ])
                                            ->columns(2)
                                            ->columnSpanFull()
                                            ->live()
                                            ->afterStateUpdated(fn(Set $set, mixed $state): bool => self::syncDocumentNumberFields($set, $state, self::otherDocumentNumberFields()))
                                            ->hidden(fn(Get $get): bool => ! self::isYes($get('tem_documentacao')))
                                            ->dehydratedWhenHidden(),
                                        TextInput::make('numero_nis')
                                            ->label('Numero do NIS/PIS')
                                            ->mask('999.99999.99-9')
                                            ->maxLength(14)
                                            ->hidden(fn(Get $get): bool => ! self::hasSelectedDocument($get, 'documentos_outros', 'nis'))
                                            ->dehydratedWhenHidden(),
                                        TextInput::make('numero_cartao_sus')
                                            ->label('Numero do cartao do SUS')
                                            ->mask('999 9999 9999 9999')
                                            ->maxLength(18)
                                            ->hidden(fn(Get $get): bool => ! self::hasSelectedDocument($get, 'documentos_outros', 'cartao_sus'))
                                            ->dehydratedWhenHidden(),
                                    ]),
                                ]),
                        ]),
                    Step::make('Saude')
                        ->schema([
                            Section::make('Trabalho, contato e saude')
                                ->schema([
                                    Grid::make([
                                        'default' => 1,
                                        'md' => 2,
                                    ])->schema([
                                        Radio::make('trabalha')
                                            ->label('Trabalha?')
                                            ->boolean('Sim', 'Nao')
                                            ->inline()
                                            ->default(false)
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, mixed $state): void {
                                                if (self::isYes($state)) {
                                                    return;
                                                }

                                                $set('nome_da_empresa_que_trabalha', null);
                                            }),
                                        TextInput::make('nome_da_empresa_que_trabalha')
                                            ->label('Nome da empresa em que trabalha')
                                            ->hidden(fn(Get $get): bool => ! self::isYes($get('trabalha')))
                                            ->dehydratedWhenHidden(),
                                        Radio::make('tem_telefone')
                                            ->label('Tem telefone?')
                                            ->boolean('Sim', 'Nao')
                                            ->inline()
                                            ->default(false)
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, mixed $state): void {
                                                if (self::isYes($state)) {
                                                    return;
                                                }

                                                $set('numero_do_telefone', null);
                                            }),
                                        TextInput::make('numero_do_telefone')
                                            ->label('Numero do telefone')
                                            ->mask('(99) 99999-9999')
                                            ->hidden(fn(Get $get): bool => ! self::isYes($get('tem_telefone')))
                                            ->dehydratedWhenHidden(),
                                        Radio::make('tem_meio_de_encaminhamento')
                                            ->label('Tem meio de encaminhamento?')
                                            ->boolean('Sim', 'Nao')
                                            ->inline()
                                            ->default(false)
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, mixed $state): void {
                                                if (self::isYes($state)) {
                                                    return;
                                                }

                                                $set('meio_de_encaminhamento', null);
                                                $set('outro_meio_de_encaminhamento_qual', null);
                                            }),
                                        CheckboxList::make('meio_de_encaminhamento')
                                            ->label('Selecione os meios de encaminhamento')
                                            ->options([
                                                'POP' => 'POP',
                                                'Centro Religioso' => 'Centro Religioso',
                                                'CRAS' => 'CRAS',
                                                'CREAS' => 'CREAS',
                                                'Familiares/amigos' => 'Familiares/amigos',
                                                'Hospital Geral' => 'Hospital Geral',
                                                'Consultorio de Rua' => 'Consultorio de Rua',
                                                'Posto de Saude' => 'Posto de Saude',
                                                'Programa do Governo do Estado' => 'Programa do Governo do Estado',
                                                'Programa da Prefeitura' => 'Programa da Prefeitura',
                                                'CAPS AD III' => 'CAPS AD III',
                                                'CAPS' => 'CAPS',
                                                'Sozinho' => 'Sozinho',
                                                'Unidade de Acolhimento' => 'Unidade de Acolhimento',
                                                'Outra Unidade de Saude' => 'Outra Unidade de Saude',
                                                'Outro meio de acolhimento' => 'Outro meio de acolhimento',
                                            ])
                                            ->columns(2)
                                            ->columnSpanFull()
                                            ->live()
                                            ->hidden(fn(Get $get): bool => ! self::isYes($get('tem_meio_de_encaminhamento')))
                                            ->dehydratedWhenHidden()
                                            ->afterStateUpdated(function (Get $get, Set $set): void {
                                                if (in_array('Outro meio de acolhimento', $get('meio_de_encaminhamento') ?? [], true)) {
                                                    return;
                                                }

                                                $set('outro_meio_de_encaminhamento_qual', null);
                                            }),
                                        TextInput::make('outro_meio_de_encaminhamento_qual')
                                            ->label('Outro meio de encaminhamento: qual?')
                                            ->hidden(fn(Get $get): bool => self::shouldHideOutroMeioDeEncaminhamento($get))
                                            ->dehydratedWhenHidden(),
                                        TextInput::make('indicacao')
                                            ->label('Indicacao'),
                                        Radio::make('toma_medicamento')
                                            ->label('Toma medicamento?')
                                            ->boolean('Sim', 'Nao')
                                            ->inline()
                                            ->default(false)
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, mixed $state): void {
                                                if (self::isYes($state)) {
                                                    return;
                                                }

                                                $set('qual_sao_as_medicacao', null);
                                            }),
                                        TagsInput::make('qual_sao_as_medicacao')
                                            ->label('Quais sao as medicacoes psicoativas')
                                            ->placeholder('Digite a medicaçao e pressione Enter')
                                            ->suggestions([
                                                'Amitriptilina',
                                                'Clomipramina',
                                                'Nortriptilina',
                                                'Fluoxetina',
                                                'Bupropiona',
                                                'Carbonato de Litio',
                                                'Carbamazepina',
                                                'Valproato de Sodio',
                                                'Acido Valproico',
                                                'Haloperidol',
                                                'Clorpromazina',
                                                'Biperideno',
                                                'Clonazepam',
                                                'Diazepam',
                                                'Midazolam',
                                            ])
                                            ->reorderable()
                                            ->columnSpanFull()
                                            ->hidden(fn(Get $get): bool => ! self::isYes($get('toma_medicamento')))
                                            ->dehydratedWhenHidden(),
                                        Radio::make('tem_receituario')
                                            ->label('Tem receituario?')
                                            ->boolean('Sim', 'Nao')
                                            ->inline()
                                            ->default(false)
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, mixed $state): void {
                                                if (self::isYes($state)) {
                                                    return;
                                                }

                                                $set('receituario', null);
                                            }),
                                        FileUpload::make('receituario')
                                            ->label('Receituario')
                                            ->disk('public')
                                            ->directory('acolhidos/receituarios')
                                            ->visibility('public')
                                            ->multiple()
                                            ->acceptedFileTypes([
                                                'application/pdf',
                                                'image/*',
                                            ])
                                            ->maxFiles(10)
                                            ->moveFiles()
                                            ->openable()
                                            ->downloadable()
                                            ->reorderable()
                                            ->getUploadedFileNameForStorageUsing(fn(TemporaryUploadedFile $file, Get $get): string => self::makeUploadFileName($file, $get))
                                            ->hidden(fn(Get $get): bool => ! self::isYes($get('tem_receituario')))
                                            ->required(fn(Get $get): bool => self::isYes($get('tem_receituario')))
                                            ->dehydrated(fn(Get $get): bool => self::isYes($get('tem_receituario'))),
                                        Radio::make('exames_laboratoriais')
                                            ->label('Possui exames laboratoriais?')
                                            ->boolean('Sim', 'Nao')
                                            ->inline()
                                            ->columnSpanFull()
                                            ->live()
                                            ->default(false)
                                            ->afterStateUpdated(function (mixed $state, Set $set): void {
                                                if (self::isYes($state)) {
                                                    return;
                                                }

                                                $set('outros', null);
                                            }),
                                        TextInput::make('outros')
                                            ->label('Detalhes dos exames')
                                            ->hidden(fn(Get $get): bool => ! self::isYes($get('exames_laboratoriais')))
                                            ->dehydratedWhenHidden(),
                                    ]),
                                ]),
                        ]),
                    Step::make('Familia')
                        ->schema([
                            Section::make('Filhos e responsaveis')
                                ->schema([
                                    Grid::make([
                                        'default' => 1,
                                        'md' => 2,
                                    ])->schema([
                                        Radio::make('tem_filhos')
                                            ->label('Tem filhos?')
                                            ->boolean('Sim', 'Nao')
                                            ->inline()
                                            ->default(false)
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, mixed $state): void {
                                                if (self::isYes($state)) {
                                                    return;
                                                }

                                                $set('quantidade_filhos', null);
                                                $set('qual_o_nome_dos_filhos', null);
                                                $set('numero_telefone_filhos', null);
                                                $set('quem_responsavel_criancas', null);
                                                $set('pensao_alimenticia', null);
                                                $set('possui_contato_dos_filhos', null);
                                            }),
                                        TextInput::make('profissional_referencia_acolhido_instituicao')
                                            ->label('Profissional de referencia na instituicao')
                                            ->columnSpanFull(),
                                        TextInput::make('quantidade_filhos')
                                            ->label('Quantidade de filhos')
                                            ->numeric()
                                            ->hidden(fn(Get $get): bool => ! self::isYes($get('tem_filhos')))
                                            ->dehydratedWhenHidden(),
                                        RichEditor::make('qual_o_nome_dos_filhos')
                                            ->label('Nome dos filhos')
                                            ->columnSpanFull()
                                            ->hidden(fn(Get $get): bool => ! self::isYes($get('tem_filhos')))
                                            ->dehydratedWhenHidden(),
                                        TextInput::make('numero_telefone_filhos')
                                            ->label('Numero de telefone dos filhos')
                                            ->mask('(99) 99999-9999')
                                            ->hidden(fn(Get $get): bool => ! self::isYes($get('tem_filhos')))
                                            ->dehydratedWhenHidden(),
                                        TextInput::make('quem_responsavel_criancas')
                                            ->label('Quem e responsavel pelas criancas')
                                            ->hidden(fn(Get $get): bool => ! self::isYes($get('tem_filhos')))
                                            ->dehydratedWhenHidden(),
                                        Radio::make('pensao_alimenticia')
                                            ->label('Recebe pensao alimenticia?')
                                            ->boolean('Sim', 'Nao')
                                            ->inline()
                                            ->hidden(fn(Get $get): bool => ! self::isYes($get('tem_filhos')))
                                            ->dehydratedWhenHidden(),
                                        Radio::make('possui_contato_dos_filhos')
                                            ->label('Possui contato com os filhos?')
                                            ->boolean('Sim', 'Nao')
                                            ->inline()
                                            ->hidden(fn(Get $get): bool => ! self::isYes($get('tem_filhos')))
                                            ->dehydratedWhenHidden(),
                                    ]),
                                ]),
                            Section::make('interventor do acolhido')
                                ->schema([
                                    Grid::make([
                                        'default' => 1,
                                        'md' => 2,
                                    ])->schema([
                                        TextInput::make('interventor_nome_completo')
                                            ->label('Nome completo'),
                                        TextInput::make('interventor_cpf')
                                            ->label('CPF')
                                            ->mask('999.999.999-99')
                                            ->maxLength(14),
                                        TextInput::make('interventor_rg')
                                            ->label('RG'),
                                        TextInput::make('interventor_exp')
                                            ->label('EXP'),
                                        Select::make('interventor_rg_uf')
                                            ->label('UF do RG')
                                            ->options(self::getBrazilianStates())
                                            ->searchable(),
                                        TextInput::make('interventor_profissao')
                                            ->label('Profissao'),
                                        DatePicker::make('interventor_data_nascimento')
                                            ->label('Data de nascimento'),
                                        TextInput::make('interventor_residente')
                                            ->label('Residente'),
                                        TextInput::make('interventor_complemento')
                                            ->label('Complemento'),
                                        TextInput::make('interventor_bairro')
                                            ->label('Bairro'),
                                        TextInput::make('interventor_cidade')
                                            ->label('Cidade'),
                                        Select::make('interventor_endereco_uf')
                                            ->label('UF')
                                            ->options(self::getBrazilianStates())
                                            ->searchable(),
                                        TextInput::make('interventor_telefone_contato')
                                            ->label('Telefone para contato')
                                            ->mask('(99) 99999-9999'),
                                    ]),
                                ]),
                        ]),
                ])
                    ->columnSpanFull(),
            ]);
    }

    private static function shouldHideNomeDoConjuge(Get $get): bool
    {
        return in_array((string) $get('estado_civil'), ['solteiro', 'viuvo'], true);
    }

    /**
     * @return array<string, string>
     */
    private static function getBrazilianStates(): array
    {
        return [
            'AC' => 'Acre',
            'AL' => 'Alagoas',
            'AP' => 'Amapa',
            'AM' => 'Amazonas',
            'BA' => 'Bahia',
            'CE' => 'Ceara',
            'DF' => 'Distrito Federal',
            'ES' => 'Espirito Santo',
            'GO' => 'Goias',
            'MA' => 'Maranhao',
            'MT' => 'Mato Grosso',
            'MS' => 'Mato Grosso do Sul',
            'MG' => 'Minas Gerais',
            'PA' => 'Para',
            'PB' => 'Paraiba',
            'PR' => 'Parana',
            'PE' => 'Pernambuco',
            'PI' => 'Piaui',
            'RJ' => 'Rio de Janeiro',
            'RN' => 'Rio Grande do Norte',
            'RS' => 'Rio Grande do Sul',
            'RO' => 'Rondonia',
            'RR' => 'Roraima',
            'SC' => 'Santa Catarina',
            'SP' => 'Sao Paulo',
            'SE' => 'Sergipe',
            'TO' => 'Tocantins',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function getBrazilianEducationLevels(): array
    {
        return [
            'nao_alfabetizado' => 'Nao alfabetizado(a)',
            'alfabetizado' => 'Alfabetizado(a)',
            'ensino_fundamental_incompleto' => 'Ensino fundamental incompleto',
            'ensino_fundamental_completo' => 'Ensino fundamental completo',
            'ensino_medio_incompleto' => 'Ensino medio incompleto',
            'ensino_medio_completo' => 'Ensino medio completo',
            'ensino_tecnico_incompleto' => 'Ensino tecnico incompleto',
            'ensino_tecnico_completo' => 'Ensino tecnico completo',
            'ensino_superior_incompleto' => 'Ensino superior incompleto',
            'ensino_superior_completo' => 'Ensino superior completo',
            'pos_graduacao_incompleta' => 'Pos-graduacao incompleta',
            'pos_graduacao_completa' => 'Pos-graduacao completa',
            'mestrado_incompleto' => 'Mestrado incompleto',
            'mestrado_completo' => 'Mestrado completo',
            'doutorado_incompleto' => 'Doutorado incompleto',
            'doutorado_completo' => 'Doutorado completo',
            'eja' => 'Educacao de Jovens e Adultos (EJA)',
            'supletivo' => 'Supletivo',
        ];
    }

    public static function getBrazilianEducationLevelLabel(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        return self::getBrazilianEducationLevels()[$value] ?? $value;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function prepareForPersistence(array $data): array
    {
        $data['user_id'] ??= auth()->id();

        foreach ([
            'ativo',
            'tem_documentacao',
            'moradia_propria',
            'mora_em_casa_aluguada',
            'trabalha',
            'tem_telefone',
            'tem_meio_de_encaminhamento',
            'toma_medicamento',
            'tem_receituario',
            'exames_laboratoriais',
            'tem_filhos',
        ] as $field) {
            $data[$field] = self::isYes($data[$field] ?? false);
        }

        foreach ([
            'pensao_alimenticia',
            'possui_contato_dos_filhos',
        ] as $field) {
            $data[$field] = array_key_exists($field, $data) && $data[$field] !== null
                ? self::isYes($data[$field])
                : null;
        }

        if (filled($data['avatar'] ?? null)) {
            $data['avatar'] = self::resolveAvatarPath((string) $data['avatar']);
        }

        if (in_array((string) ($data['estado_civil'] ?? null), ['solteiro', 'viuvo'], true)) {
            $data['nome_do_conjuge'] = null;
        }

        if ($data['moradia_propria']) {
            $data['mora_em_casa_aluguada'] = false;
        }

        if (! $data['mora_em_casa_aluguada']) {
            $data['quanto_tempo_de_aluguel'] = null;
            $data['em_qual_regiao'] = null;
        }

        if (! $data['tem_documentacao']) {
            $data['razao_caso_nao_tenha_documentacao'] = $data['razao_caso_nao_tenha_documentacao'] ?? null;
            $data['documentos_civis'] = null;
            $data['documentos_outros'] = null;
            $data = self::clearDocumentNumberValues($data);
        } else {
            $data['razao_caso_nao_tenha_documentacao'] = null;
            $data['documentos_civis'] = self::normalizeList($data['documentos_civis'] ?? []);
            $data['documentos_outros'] = self::normalizeList($data['documentos_outros'] ?? []);
            $data = self::normalizeDocumentNumberValues($data);
        }

        if (! $data['trabalha']) {
            $data['nome_da_empresa_que_trabalha'] = null;
        }

        $data['escolaridade'] = self::normalizeNullableString($data['escolaridade'] ?? null);
        $data['escolaridade_observacao'] = self::normalizeNullableString($data['escolaridade_observacao'] ?? null);

        if (! $data['tem_telefone']) {
            $data['numero_do_telefone'] = null;
        }

        if (! $data['tem_meio_de_encaminhamento']) {
            $data['meio_de_encaminhamento'] = null;
            $data['outro_meio_de_encaminhamento_qual'] = null;
        } else {
            $data['meio_de_encaminhamento'] = self::normalizeList($data['meio_de_encaminhamento'] ?? []);

            if (! in_array('Outro meio de acolhimento', $data['meio_de_encaminhamento'], true)) {
                $data['outro_meio_de_encaminhamento_qual'] = null;
            }
        }

        if (! $data['toma_medicamento']) {
            $data['qual_sao_as_medicacao'] = null;
            $data['tem_receituario'] = false;
            $data['receituario'] = null;
        } else {
            $data['qual_sao_as_medicacao'] = self::normalizeList($data['qual_sao_as_medicacao'] ?? []);

            if (! $data['tem_receituario']) {
                $data['receituario'] = null;
            } else {
                $data['receituario'] = self::normalizeList($data['receituario'] ?? []);
            }
        }

        if (! $data['exames_laboratoriais']) {
            $data['outros'] = null;
        }

        if (! $data['tem_filhos']) {
            $data['quantidade_filhos'] = null;
            $data['qual_o_nome_dos_filhos'] = null;
            $data['numero_telefone_filhos'] = null;
            $data['quem_responsavel_criancas'] = null;
            $data['pensao_alimenticia'] = null;
            $data['possui_contato_dos_filhos'] = null;
        }

        $data['responsavel_pela_intervencao_do_acolhido'] = self::normalizeNullableString(
            $data['interventor_nome_completo']
                ?? $data['responsavel_pela_intervencao_do_acolhido']
                ?? null
        );

        return $data;
    }

    private static function notificationTitle(string $event): string
    {
        return match ($event) {
            'created' => 'Novo acolhido',
            'updated' => 'Acolhido atualizado',
            'deleted' => 'Acolhido removido',
            'restored' => 'Acolhido restaurado',
            'forceDeleted' => 'Acolhido removido em definitivo',
            default => 'Acolhido atualizado',
        };
    }

    private static function notificationBody(Acolhido $acolhido, string $event): string
    {
        $name = $acolhido->nome_completo_paciente ?: 'Um acolhido';
        $responsible = auth()->user()?->name ?: 'Sistema';

        $action = match ($event) {
            'created' => 'cadastrado',
            'updated' => 'editado',
            'deleted' => 'excluido',
            'restored' => 'restaurado',
            'forceDeleted' => 'excluido em definitivo',
            default => 'atualizado',
        };

        return "{$name} foi {$action} por {$responsible}.";
    }

    private static function notificationIcon(string $event): string
    {
        return match ($event) {
            'created' => 'heroicon-o-user-plus',
            'updated' => 'heroicon-o-pencil-square',
            'deleted', 'forceDeleted' => 'heroicon-o-trash',
            'restored' => 'heroicon-o-arrow-path',
            default => 'heroicon-o-bell-alert',
        };
    }

    private static function notificationKey(Acolhido $acolhido, string $event): string
    {
        return "acolhido_{$event}_{$acolhido->getKey()}_" . ($acolhido->updated_at?->timestamp ?? now()->timestamp);
    }

    private static function notificationUrl(Acolhido $acolhido): string
    {
        return AcolhidoResource::getUrl('view', ['record' => $acolhido]);
    }

    private static function shouldHideQuantoTempoDeAluguel(Get $get): bool
    {
        if (self::isYes($get('moradia_propria'))) {
            return true;
        }

        return ! self::isYes($get('mora_em_casa_aluguada'));
    }

    private static function shouldHideOutroMeioDeEncaminhamento(Get $get): bool
    {
        if (! self::isYes($get('tem_meio_de_encaminhamento'))) {
            return true;
        }

        return ! in_array('Outro meio de acolhimento', $get('meio_de_encaminhamento') ?? [], true);
    }

    private static function hasSelectedDocument(Get $get, string $field, string $document): bool
    {
        return in_array($document, $get($field) ?? [], true);
    }

    private static function isYes(mixed $value): bool
    {
        $normalized = Str::of((string) $value)
            ->lower()
            ->ascii()
            ->trim()
            ->value();

        return in_array($value, [true, 1, '1'], true)
            || in_array($normalized, ['true', 'sim', 'yes', 'on'], true);
    }

    private static function resolveAvatarPath(?string $path): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return null;
        }

        $disk = Storage::disk('public');

        foreach (
            array_unique([
                $path,
                'avatars/' . basename($path),
                'acolhidos/avatars/' . basename($path),
            ]) as $candidate
        ) {
            if ($disk->exists($candidate)) {
                return $candidate;
            }
        }

        return $path;
    }

    private static function makeUploadFileName(TemporaryUploadedFile $file, Get $get): string
    {
        $baseName = Str::of((string) $get('nome_completo_paciente'))
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '_')
            ->trim('_')
            ->value();

        if ($baseName === '') {
            $baseName = 'acolhido';
        }

        return sprintf(
            '%s_%s.%s',
            $baseName,
            now()->format('Y_m_d_His'),
            $file->getClientOriginalExtension(),
        );
    }

    /**
     * @return array<string, string>
     */
    private static function civilDocumentNumberFields(): array
    {
        return [
            'rg' => 'numero_rg',
            'cpf' => 'numero_cpf',
            'certidao_nascimento' => 'numero_certidao_nascimento',
            'certidao_casamento' => 'numero_certidao_casamento',
            'carteira_trabalho' => 'numero_carteira_trabalho',
            'titulo_eleitor' => 'numero_titulo_eleitor',
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function otherDocumentNumberFields(): array
    {
        return [
            'nis' => 'numero_nis',
            'cartao_sus' => 'numero_cartao_sus',
        ];
    }

    private static function syncDocumentNumberFields(Set $set, mixed $state, array $fieldMap): bool
    {
        $selectedDocuments = is_array($state) ? $state : [];

        foreach ($fieldMap as $document => $field) {
            if (! in_array($document, $selectedDocuments, true)) {
                $set($field, null);
            }
        }

        return true;
    }

    private static function clearDocumentNumberFields(Set $set): void
    {
        foreach (array_merge(self::civilDocumentNumberFields(), self::otherDocumentNumberFields()) as $field) {
            $set($field, null);
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function clearDocumentNumberValues(array $data): array
    {
        foreach (array_merge(self::civilDocumentNumberFields(), self::otherDocumentNumberFields()) as $field) {
            $data[$field] = null;
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function normalizeDocumentNumberValues(array $data): array
    {
        $selectedCivilDocuments = $data['documentos_civis'] ?? [];
        $selectedOtherDocuments = $data['documentos_outros'] ?? [];

        foreach (self::civilDocumentNumberFields() as $document => $field) {
            $data[$field] = in_array($document, $selectedCivilDocuments, true)
                ? self::normalizeNullableString($data[$field] ?? null)
                : null;
        }

        foreach (self::otherDocumentNumberFields() as $document => $field) {
            $data[$field] = in_array($document, $selectedOtherDocuments, true)
                ? self::normalizeNullableString($data[$field] ?? null)
                : null;
        }

        return $data;
    }

    private static function normalizeNullableString(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    /**
     * @param  mixed  $value
     * @return array<int, mixed>
     */
    private static function normalizeList(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        return array_values(array_filter($value, static fn(mixed $item): bool => filled($item)));
    }
}
