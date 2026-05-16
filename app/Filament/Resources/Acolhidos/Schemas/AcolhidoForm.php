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
                                            ->relationship('user', 'name')
                                            ->searchable()
                                            ->preload()
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
                                            )
                                            ->required(),
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
                                            ->default('Nao informado')
                                            ->required(),
                                        TextInput::make('nome_do_pai')
                                            ->label('Nome do pai')
                                            ->required(),
                                        TextInput::make('cor_da_pele')
                                            ->label('Cor da pele')
                                            ->required(),
                                        TextInput::make('escolaridade')
                                            ->label('Escolaridade')
                                            ->required(),
                                        TextInput::make('profissao')
                                            ->label('Profissao')
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
                                        TextInput::make('uf_municipio_do_paciente')
                                            ->label('UF')
                                            ->required(),
                                        Radio::make('moradia_propria')
                                            ->label('Moradia propria')
                                            ->boolean('Sim', 'Nao')
                                            ->inline()
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
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, mixed $state): void {
                                                if (self::isYes($state)) {
                                                    $set('razao_caso_nao_tenha_documentacao', null);
                                                    return;
                                                }

                                                $set('documentos_civis', null);
                                                $set('documentos_outros', null);
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
                                            ->hidden(fn(Get $get): bool => ! self::isYes($get('tem_documentacao')))
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
                                            ->hidden(fn(Get $get): bool => ! self::isYes($get('tem_documentacao')))
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
                                        TextInput::make('responsavel_pela_intervencao_do_acolhido')
                                            ->label('Responsavel pela intervencao do acolhido')
                                            ->required()
                                            ->columnSpanFull(),
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
                        ]),
                ])
                    ->columnSpanFull(),
            ]);
    }

    private static function shouldHideNomeDoConjuge(Get $get): bool
    {
        return in_array((string) $get('estado_civil'), ['solteiro', 'viuvo'], true);
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
}
