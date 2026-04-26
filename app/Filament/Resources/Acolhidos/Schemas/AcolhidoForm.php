<?php

namespace App\Filament\Resources\Acolhidos\Schemas;

use App\Services\CorreiosCepService;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;


class AcolhidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'md' => 2,
                ])
                    ->columnSpanFull()
                    ->schema([
                        Group::make([
                            Section::make('Cadastro e identificação')
                                ->schema([
                                    Grid::make([
                                        'default' => 1,
                                        'md' => 2,
                                    ])->schema([
                                        Select::make('user_id')
                                            ->label('Quem está cadastrando?')
                                            ->required()
                                            ->placeholder('Selecione um usuário')
                                            ->relationship('users', 'name'),
                                        FileUpload::make('avatar')
                                            ->image()
                                            ->disk('public')
                                            ->directory('avatars')
                                            ->visibility('public')
                                            ->maxFiles(1)
                                            ->required(),
                                        TextInput::make('nome_completo_paciente')
                                            ->label('Nome completo do paciente')
                                            ->required(),
                                        DatePicker::make('data_nascimento')
                                            ->label('Data de nascimento')
                                            ->required(),
                                        Radio::make('estado_civil')
                                            ->label('Estado civil')
                                            ->options([
                                                'solteiro' => 'Solteiro',
                                                'casado' => 'Casado',
                                                'viuvo' => 'Viúvo',
                                            ])->inline()
                                            ->afterStateUpdated(function (Set $set, mixed $state): void {
                                                if (in_array(Str::of((string) $state)->lower()->ascii()->value(), ['solteiro', 'viuvo'], true)) {
                                                    $set('nome_do_conjuge', null);
                                                }
                                            })
                                            ->live(),
                                        TextInput::make('nome_do_conjuge')
                                            ->label('Nome do Cônjuge')
                                            ->visible(
                                                fn($get) =>
                                                !in_array(
                                                    Str::of((string) $get('estado_civil'))->lower()->ascii()->value(),
                                                    ['solteiro', 'viuvo'],
                                                    true
                                                )
                                            )
                                            ->required(
                                                fn($get) =>
                                                !in_array(
                                                    Str::of((string) $get('estado_civil'))->lower()->ascii()->value(),
                                                    ['solteiro', 'viuvo'],
                                                    true
                                                )
                                            ),
                                        TextInput::make('nome_da_mae')
                                            ->label('Nome da mãe')
                                            ->placeholder('Campo obrigatório'),
                                        TextInput::make('nome_do_pai')
                                            ->label('Nome do pai')
                                            ->placeholder('Campo obrigatório'),
                                        TextInput::make('cor_da_pele')
                                            ->label('Cor da pele')
                                            ->required(),
                                        TextInput::make('escolaridade')
                                            ->label('Escolaridade')
                                            ->required(),
                                        TextInput::make('profissao                  ')
                                            ->label('Profissão')
                                            ->required(),
                                    ]),
                                ])->collapsed(true),
                            Section::make('Endereço e moradia')
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
                                            })
                                            ->placeholder('Campo não obrigatório'),
                                        TextInput::make('endereco_paciente')
                                            ->label('Endereço do paciente')
                                            ->required(),
                                        TextInput::make('bairro_do_paciente')
                                            ->label('Bairro do paciente')
                                            ->required(),
                                        TextInput::make('municipio_do_paciente')
                                            ->label('Município')
                                            ->required(),
                                        TextInput::make('uf_municipio_do_paciente')
                                            ->label('UF')
                                            ->required(),
                                        Radio::make('moradia_propria')
                                            ->label('Moradia própria')
                                            ->boolean('Sim', 'Não')
                                            ->inline()
                                            ->required()
                                            ->afterStateUpdated(function (Set $set, mixed $state): void {
                                                if (! self::isYes($state)) {
                                                    return;
                                                }

                                                $set('Mora_Em_Casa_Aluguada', false);
                                                $set('Quanto_Tempo_de_Aluguel', null);
                                                $set('em_qual_regiao', null);
                                            })
                                            ->live(),
                                        Radio::make('Mora_Em_Casa_Aluguada')
                                            ->label('Mora em casa alugada?')
                                            ->boolean('Sim', 'Não')
                                            ->inline()
                                            ->required()
                                            ->dehydratedWhenHidden()
                                            ->afterStateUpdated(function (Set $set, mixed $state): void {
                                                if (self::isYes($state)) {
                                                    return;
                                                }

                                                $set('Quanto_Tempo_de_Aluguel', null);
                                                $set('em_qual_regiao', null);
                                            })
                                            ->live()
                                            ->hidden(fn(Get $get): bool => self::shouldHideMoradiaAlugadaFields($get)),
                                        Select::make('Quanto_Tempo_de_Aluguel')
                                            ->label('Quanto tempo de aluguel')
                                            ->options([
                                                '6 meses' => '6 meses',
                                                'Mais de 1 ano' => 'Mais de 1 ano',
                                            ])
                                            ->placeholder('Campo não obrigatório')
                                            ->dehydratedWhenHidden()
                                            ->hidden(fn(Get $get): bool => self::shouldHideQuantoTempoDeAluguel($get)),
                                        TextInput::make('em_qual_regiao')
                                            ->label('Em qual região')
                                            ->placeholder('Campo não obrigatório')
                                            ->dehydratedWhenHidden()
                                            ->hidden(fn(Get $get): bool => self::shouldHideQuantoTempoDeAluguel($get)),
                                    ]),
                                ])->collapsed(true),
                        ]),
                        Group::make([
                            Section::make('Documentação')
                                ->schema([
                                    Grid::make([
                                        'default' => 1,
                                        'md' => 2,
                                    ])->schema([
                                        Radio::make('tem_documentacao')
                                            ->label('Tem documentação?')
                                            ->options([
                                                1 => 'Sim',
                                                0 => 'Não',
                                            ])
                                            ->live(),
                                        TextInput::make('razao_caso_nao_tenha_documentacao')
                                            ->label('Caso não tenha documento')
                                            ->visible(fn($get) => $get('tem_documentacao') == 0)
                                            ->required(fn($get) => $get('tem_documentacao') == 0),
                                        Section::make('Documentações')
                                            ->schema([
                                                CheckboxList::make('documentos_civis')
                                                    ->label('Documentos Civis')
                                                    ->options([
                                                        'rg' => 'RG',
                                                        'cpf' => 'CPF',
                                                        'certidao_nascimento' => 'Certidão de Nascimento',
                                                        'certidao_casamento' => 'Certidão de Casamento',
                                                        'carteira_trabalho' => 'Carteira de Trabalho',
                                                        'titulo_eleitor'    => 'Título de Eleitor',
                                                    ])
                                                    ->columns(2),

                                                CheckboxList::make('documentos_outros')
                                                    ->label('Outros Documentos')
                                                    ->options([
                                                        'comprovante_residencia'    =>  'Comprovante de residência',
                                                        'nis' => 'NIS/PIS',
                                                        'cartao_sus' => 'Cartão do SUS',
                                                        'certidao_antecedentes' =>  'Certidão de antecedentes',
                                                    ])
                                                    ->columns(2),
                                            ])->columnSpanFull()
                                            ->hidden(fn(Get $get) => !$get('tem_documentacao')),


                                    ])
                                ])->collapsed(true),
                            Section::make('Trabalho, contato e saúde')
                                ->schema([
                                    Grid::make([
                                        'default' => 1,
                                        'md' => 2,
                                    ])->schema([
                                        Radio::make('trabalha')
                                            ->options([
                                                1 => 'Sim',
                                                0 => 'Não',
                                            ])
                                            ->live(),

                                        TextInput::make('nome_da_empresa_que_trabalha')
                                            ->hidden(fn(Get $get) => !$get('trabalha')),
                                        TextInput::make('nome_da_empresa_que_trabalha')
                                            ->label('Nome da empresa em que trabalha')
                                            ->dehydratedWhenHidden()
                                            ->hidden(fn(Get $get): bool => self::shouldHideEmpresaField($get))
                                            ->placeholder('Campo não obrigatório'),
                                        Radio::make('tem_telefone')
                                            ->options([
                                                1 => 'Sim',
                                                0 => 'Não',
                                            ])
                                            ->live(),

                                        TextInput::make('numero_do_telefone')
                                            ->mask('(99) 99999-9999')
                                            ->hidden(fn(Get $get) => !$get('tem_telefone'))
                                            ->placeholder('Campo não obrigatório'),
                                        Radio::make('tem_meio_de_encaminhamento')
                                            ->options([
                                                true => 'Sim',
                                                false => 'Não',
                                            ])
                                            ->live(),
                                        CheckboxList::make('meio_de_encaminhamento')
                                            ->label('Selecione os meios de encaminhamento')
                                            ->columnSpanFull()
                                            ->options([
                                                'POP' => 'POP',
                                                'Centro Religioso' => 'Centro Religioso',
                                                'CRAS' => 'CRAS',
                                                'CREAS' => 'CREAS',
                                                'Familiares/amigos' => 'Familiares/amigos',
                                                'Hospital Geral' => 'Hospital Geral',
                                                'Consultório de Rua' => 'Consultório de Rua',
                                                'Posto de Saúde' => 'Posto de Saúde',
                                                'Programa do Governo do Estado' => 'Programa do Governo do Estado',
                                                'Programa da Prefeitura' => 'Programa da Prefeitura',
                                                'CAPS AD III' => 'CAPS AD III',
                                                'CAPS' => 'CAPS',
                                                'Sozinho' => 'Sozinho',
                                                'Unidade de Acolhimento' => 'Unidade de Acolhimento',
                                                'Outra Unidade de Saúde' => 'Outra Unidade de Saúde',
                                                'Outro meio de acolhimento' => 'Outro meio de acolhimento',
                                            ])
                                            ->columns(2)
                                            ->live()
                                            ->dehydratedWhenHidden()
                                            ->afterStateUpdated(function (Get $get, Set $set): void {
                                                if (in_array('Outro meio de acolhimento', $get('meio_de_encaminhamento') ?? [], true)) {
                                                    return;
                                                }

                                                $set('outro_meio_de_encaminhamento_qual', null);
                                            })
                                            ->hidden(fn(Get $get): bool => ! self::isYes($get('tem_meio_de_encaminhamento'))),
                                        TextInput::make('outro_meio_de_encaminhamento_qual')
                                            ->label('Outro meio de encaminhamento: qual?')
                                            ->dehydratedWhenHidden()
                                            ->hidden(fn(Get $get): bool => self::shouldHideOutroMeioDeEncaminhamento($get))
                                            ->placeholder('Campo não obrigatório'),
                                        TextInput::make('indicacao')
                                            ->label('Indicação')
                                            ->placeholder('Campo não obrigatório'),
                                        Radio::make('toma_medicamento')
                                            ->label('Toma medicamento?')
                                            ->options([
                                                1 => 'Sim',
                                                0 => 'Não',
                                            ])
                                            ->afterStateUpdated(function (Set $set, mixed $state): void {
                                                if ($state == 1) {
                                                    return;
                                                }

                                                $set('qual_sao_as_medicacao', []);
                                            })
                                            ->live()
                                            ->required(),

                                        CheckboxList::make('qual_sao_as_medicacao')
                                            ->label('Quais são as medicações psicoativas')
                                            ->columnSpanFull()
                                            ->options([
                                                'Amitriptilina' => 'Amitriptilina',
                                                'Clomipramina' => 'Clomipramina',
                                                'Nortriptilina' => 'Nortriptilina',
                                                'Fluoxetina' => 'Fluoxetina',
                                                'Bupropiona' => 'Bupropiona',
                                                'Carbonato de Lítio' => 'Carbonato de Lítio',
                                                'Carbamazepina' => 'Carbamazepina',
                                                'Valproato de Sódio' => 'Valproato de Sódio',
                                                'Ácido Valpróico' => 'Ácido Valpróico',
                                                'Haloperidol' => 'Haloperidol',
                                                'Clorpromazina' => 'Clorpromazina',
                                                'Biperideno' => 'Biperideno',
                                                'Clonazepam' => 'Clonazepam',
                                                'Diazepam' => 'Diazepam',
                                                'Midazolam' => 'Midazolam',
                                            ])
                                            ->columns(2)
                                            ->visible(fn(Get $get) => $get('toma_medicamento') == 1)
                                            ->dehydratedWhenHidden(),
                                        Radio::make('tem_receituario')
                                            ->options([
                                                1 => 'Sim',
                                                0 => 'Não',
                                            ])
                                            ->inline() // opcional (fica lado a lado)
                                            ->live(),
                                        FileUpload::make('receituario')
                                            ->disk('public')
                                            ->directory('receituario')
                                            ->visibility('public')
                                            ->maxFiles(1)
                                            ->hidden(fn(Get $get) => !$get('tem_receituario'))
                                            ->required(fn(Get $get) => $get('tem_receituario') === 'sim'),

                                        CheckboxList::make('exames_laboratoriais')
                                            ->options([
                                                'HIV' => 'HIV',
                                                'Sífilis' => 'Sífilis',
                                                'Hepatite B' => 'Hepatite B',
                                                'Hepatite C' => 'Hepatite C',
                                                'HTLV' => 'HTLV',
                                                'Outros' => 'Outros',

                                            ])->columns(2)
                                            ->label('Exames laboratoriais')
                                            ->required(),
                                        TextInput::make('outros')
                                            ->label('Outros')
                                            ->required(),
                                    ]),
                                ])->collapsed(true),
                            Section::make('Filhos e responsáveis')
                                ->schema([
                                    Grid::make([
                                        'default' => 1,
                                        'md' => 2,
                                    ])->schema([
                                        Radio::make('tem_filhos')
                                            ->label('Tem filhos?')
                                            ->options([
                                                '1' => 'Sim',
                                                '0' => 'Não',
                                            ])
                                            ->inline()
                                            ->live(),
                                        Section::make('Filiação')
                                            ->schema([
                                                TextInput::make('quantidade_filhos')
                                                    ->label('Quantidade de filhos')
                                                    ->numeric(),

                                                RichEditor::make('nome_filhos')
                                                    ->label('Nome dos filhos')
                                                    ->columnSpanFull(),

                                                DatePicker::make('data_nascimento_filhos')
                                                    ->label('Data de nascimento'),

                                                TextInput::make('numero_telefone_filhos')
                                                    ->label('Número de telefone dos filhos')
                                                    ->mask('(99) 99999-9999'),

                                                TextInput::make('Quem_Responsavel_Criancas')
                                                    ->label('Quem é responsável pelas crianças'),

                                                Radio::make('Pensao_Alimenticia')
                                                    ->label('Recebe pensão alimentícia?')
                                                    ->boolean('Sim', 'Não'),

                                                Radio::make('Possui_Contato_dos_Filhos')
                                                    ->label('Possui contato com os filhos?')
                                                    ->boolean('Sim', 'Não'),

                                                TextInput::make('Responsavel_pela_Intervencao_do_acolhido')
                                                    ->label('Responsável pela intervenção do acolhido')
                                            ])
                                            ->visible(fn(Get $get) => $get('tem_filhos') != '0')

                                            ->columns(2)
                                            ->columnSpanFull(),

                                    ]),
                                ])->collapsed(true),
                        ]),
                    ]),
            ]);
    }

    private static function shouldHideNomeDoConjuge(Get $get): bool
    {
        $estadoCivil = Str::of((string) ($get('Estado_Civil') ?? ''))
            ->lower()
            ->ascii()
            ->value();

        return in_array($estadoCivil, ['solteiro', 'viuvo'], true);
    }

    private static function shouldHideQuantoTempoDeAluguel(Get $get): bool
    {
        if (self::isYes($get('moradia_propria'))) {
            return true;
        }

        return ! self::isYes($get('Mora_Em_Casa_Aluguada'));
    }

    private static function shouldHideMoradiaAlugadaFields(Get $get): bool
    {
        return self::isYes($get('moradia_propria'));
    }

    private static function isYes(mixed $value): bool
    {
        return in_array($value, [true, 1, '1', 'true', 'on', 'yes'], true);
    }

    protected static function shouldHideEmpresaField(Get $get): bool
    {
        return ! self::isYes($get('Trabalha'));
    }

    protected static function shouldHideOutroMeioDeEncaminhamento(Get $get): bool
    {
        if (! self::isYes($get('tem_meio_de_encaminhamento'))) {
            return true;
        }

        return ! in_array('Outro meio de acolhimento', $get('meio_de_encaminhamento') ?? [], true);
    }
}
