<?php

namespace App\Filament\Resources\SubstanciaPsicoativas;

use App\Filament\Resources\SubstanciaPsicoativas\Pages\ManageSubstanciaPsicoativas;
use App\Filament\Resources\SubstanciaPsicoativas\Pages\ViewSubstanciaPsicoativa;
use App\Models\SubstanciaPsicoativas;
use App\Models\User;
use App\Support\FilamentDatabaseNotifications;
use Barryvdh\DomPDF\Facade\Pdf;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use UnitEnum;

class SubstanciaPsicoativaResource extends Resource
{
    protected static ?string $model = SubstanciaPsicoativas::class;

    protected static string | UnitEnum | null $navigationGroup = 'Cadastros';

    protected static ?string $navigationLabel = 'Substancias psicoativas';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $modelLabel = 'substancia psicoativa';

    protected static ?string $pluralModelLabel = 'substancias psicoativas';

    protected static ?string $recordTitleAttribute = 'nome';

    public static function getRecordTitle(?\Illuminate\Database\Eloquent\Model $record): string
    {
        return $record?->acolhido?->nome_completo_paciente ?? 'Sem nome';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['acolhido.nome_completo_paciente'];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificacao do registro')
                    ->description('Vincule a ficha ao acolhido e registre as substancias psicoativas referidas como foco principal de acompanhamento.')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            Select::make('acolhido_id')
                                ->label('Acolhido')
                                ->relationship('acolhido', 'nome_completo_paciente')
                                ->searchable()
                                ->preload()
                                ->required(),
                            TagsInput::make('nome')
                                ->label('Nome da substancia')
                                ->placeholder('Digite uma substancia e pressione Enter')
                                ->helperText('Use uma tag para cada substancia informada pelo acolhido.')
                                ->separator(',')
                                ->splitKeys(['Tab', 'Enter', ','])
                                ->nestedRecursiveRules(['distinct'])
                                ->formatStateUsing(function (mixed $state): array {
                                    if (blank($state)) {
                                        return [];
                                    }

                                    if (is_array($state)) {
                                        return $state;
                                    }

                                    return collect(explode(',', (string) $state))
                                        ->map(fn(string $item): string => trim($item))
                                        ->filter()
                                        ->values()
                                        ->all();
                                })
                                ->dehydrateStateUsing(function (mixed $state): string {
                                    return collect($state ?? [])
                                        ->map(fn(mixed $item): string => trim((string) $item))
                                        ->filter()
                                        ->unique()
                                        ->implode(', ');
                                })
                                ->suggestions([
                                    'Alcool',
                                    'Crack',
                                    'Cocaina',
                                    'Maconha',
                                    'Cigarro',
                                    'Medicamentos controlados',
                                ])
                                ->required()
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Padrao de uso e consumo')
                    ->description('Descreva o padrao atual ou recente de uso com foco em frequencia, intensidade, via e ultima ocorrencia relatada.')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            TextInput::make('frequencia')
                                ->label('Frequencia de uso')
                                ->placeholder('Ex.: Diario, semanal, esporadico')
                                ->helperText('Informe a regularidade observada ou referida.')
                                ->maxLength(255),
                            TextInput::make('quantidade')
                                ->label('Quantidade')
                                ->placeholder('Ex.: 2 doses, 1 pedra, 3 cigarros')
                                ->helperText('Registre a quantidade media por episodio de uso.')
                                ->maxLength(255),
                            TextInput::make('via_administracao')
                                ->label('Via de administracao')
                                ->placeholder('Ex.: Oral, fumada, inalada')
                                ->maxLength(255),
                            TextInput::make('tempo_uso')
                                ->label('Tempo de uso')
                                ->placeholder('Ex.: 5 anos')
                                ->maxLength(255),
                            TextInput::make('ultima_vez')
                                ->label('Ultima vez que utilizou')
                                ->placeholder('Ex.: Ontem, ha 2 semanas')
                                ->maxLength(255),
                            Textarea::make('observacoes')
                                ->label('Observacoes clinicas')
                                ->placeholder('Anote informacoes relevantes sobre padrao de uso, recaidas, contexto ou sinais observados.')
                                ->rows(4)
                                ->maxLength(255)
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Contexto familiar e inicio do uso')
                    ->description('Mapeie fatores familiares e influencias relacionais associados ao inicio ou manutencao do uso.')
                    ->icon('heroicon-o-users')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            Radio::make('houve_dependentes_quimicos_familia_convivencia')
                                ->label('Houve dependentes quimicos na familia de convivencia?')
                                ->boolean('Sim', 'Nao')
                                ->inline()
                                ->live()
                                ->default(false)
                                ->afterStateUpdated(function ($set, mixed $state): void {
                                    if ($state) {
                                        return;
                                    }

                                    $set('nome_pessoa_dependente_familiar', null);
                                }),
                            TextInput::make('nome_pessoa_dependente_familiar')
                                ->label('Qual o nome da pessoa?')
                                ->helperText('Preencha apenas se houver referencia nominal relevante para o contexto.')
                                ->maxLength(255)
                                ->hidden(fn($get): bool => ! (bool) $get('houve_dependentes_quimicos_familia_convivencia'))
                                ->required(fn($get): bool => (bool) $get('houve_dependentes_quimicos_familia_convivencia'))
                                ->dehydratedWhenHidden(),
                            Radio::make('influencia_terceiro_inicio_uso')
                                ->label('Teve influencia de terceiro para o inicio do uso?')
                                ->options([
                                    'sim' => 'Sim',
                                    'nao' => 'Nao',
                                    'prefere_nao_informar' => 'Prefere nao informar',
                                ])
                                ->inline()
                                ->inlineLabel(false)
                                ->live()
                                ->required()
                                ->afterStateUpdated(function ($set, mixed $state): void {
                                    if ($state === 'sim') {
                                        return;
                                    }

                                    $set('tipo_relacao_influencia_terceiro', null);
                                })
                                ->columnSpanFull(),
                            TextInput::make('tipo_relacao_influencia_terceiro')
                                ->label('Se sim, qual o tipo de relacao?')
                                ->placeholder('Ex.: Pai, amigo, companheiro, colega')
                                ->helperText('Identifique o vinculo predominante relacionado ao inicio do uso.')
                                ->maxLength(255)
                                ->hidden(fn($get): bool => $get('influencia_terceiro_inicio_uso') !== 'sim')
                                ->required(fn($get): bool => $get('influencia_terceiro_inicio_uso') === 'sim')
                                ->dehydratedWhenHidden()
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Historico terapeutico e acolhimento')
                    ->description('Registre adesao a grupos de apoio, internações previas e experiencias anteriores de acolhimento.')
                    ->icon('heroicon-o-heart')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            Radio::make('participou_grupos_apoio')
                                ->label('Participou de grupos de apoio AA, NA ou outros?')
                                ->boolean('Sim', 'Nao')
                                ->inline()
                                ->live()
                                ->default(false)
                                ->afterStateUpdated(function ($set, mixed $state): void {
                                    if ($state) {
                                        return;
                                    }

                                    $set('qual_grupo_apoio', null);
                                }),
                            TextInput::make('qual_grupo_apoio')
                                ->label('Se sim, digite qual')
                                ->placeholder('Ex.: AA, NA, grupo comunitario, grupo religioso')
                                ->maxLength(255)
                                ->hidden(fn($get): bool => ! (bool) $get('participou_grupos_apoio'))
                                ->required(fn($get): bool => (bool) $get('participou_grupos_apoio'))
                                ->dehydratedWhenHidden(),
                            Radio::make('teve_internacoes_anteriores')
                                ->label('Teve internacoes anteriores?')
                                ->boolean('Sim', 'Nao')
                                ->inline()
                                ->live()
                                ->default(false)
                                ->afterStateUpdated(function ($set, mixed $state): void {
                                    if ($state) {
                                        return;
                                    }

                                    $set('quantas_internacoes_anteriores', null);
                                    $set('onde_internacoes_anteriores', null);
                                    $set('quando_internacoes_anteriores', null);
                                }),
                            TextInput::make('quantas_internacoes_anteriores')
                                ->label('Se sim, quantas vezes?')
                                ->placeholder('Ex.: 1 vez, 3 vezes')
                                ->maxLength(255)
                                ->hidden(fn($get): bool => ! (bool) $get('teve_internacoes_anteriores'))
                                ->required(fn($get): bool => (bool) $get('teve_internacoes_anteriores'))
                                ->dehydratedWhenHidden(),
                            TextInput::make('onde_internacoes_anteriores')
                                ->label('Onde foram as internacoes?')
                                ->placeholder('Ex.: CAPS, comunidade terapeutica, hospital')
                                ->maxLength(255)
                                ->hidden(fn($get): bool => ! (bool) $get('teve_internacoes_anteriores'))
                                ->required(fn($get): bool => (bool) $get('teve_internacoes_anteriores'))
                                ->dehydratedWhenHidden(),
                            TextInput::make('quando_internacoes_anteriores')
                                ->label('Quando ocorreram?')
                                ->placeholder('Ex.: 2021, ha 6 meses, adolescencia')
                                ->maxLength(255)
                                ->hidden(fn($get): bool => ! (bool) $get('teve_internacoes_anteriores'))
                                ->required(fn($get): bool => (bool) $get('teve_internacoes_anteriores'))
                                ->dehydratedWhenHidden(),
                            Radio::make('lembra_tempo_acolhimento_anterior')
                                ->label('Lembra o tempo de acolhimento anterior?')
                                ->boolean('Sim', 'Nao')
                                ->inline()
                                ->live()
                                ->default(false)
                                ->afterStateUpdated(function ($set, mixed $state): void {
                                    if ($state) {
                                        return;
                                    }

                                    $set('tempo_acolhimento_anterior', null);
                                }),
                            TextInput::make('tempo_acolhimento_anterior')
                                ->label('Se sim, quanto tempo foi?')
                                ->placeholder('Ex.: 30 dias, 6 meses, 1 ano')
                                ->maxLength(255)
                                ->hidden(fn($get): bool => ! (bool) $get('lembra_tempo_acolhimento_anterior'))
                                ->required(fn($get): bool => (bool) $get('lembra_tempo_acolhimento_anterior'))
                                ->dehydratedWhenHidden(),
                        ]),
                    ]),
                Section::make('Aspectos juridicos e institucionais')
                    ->description('Registre situacoes prisionais, medidas em curso e antecedentes judiciais relevantes para o acompanhamento.')
                    ->icon('heroicon-o-scale')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            Radio::make('esteve_unidade_prisional_ou_similar')
                                ->label('Esteve em uma unidade prisional ou similar?')
                                ->options([
                                    'sim' => 'Sim',
                                    'nao' => 'Nao',
                                    'prefere_nao_informar' => 'Prefere nao informar',
                                ])
                                ->inline()
                                ->live()
                                ->required()
                                ->afterStateUpdated(function ($set, mixed $state): void {
                                    if ($state === 'sim') {
                                        return;
                                    }

                                    $set('periodo_unidade_prisional', null);
                                    $set('motivo_unidade_prisional', null);
                                })
                                ->columnSpanFull(),
                            TextInput::make('periodo_unidade_prisional')
                                ->label('Se sim, qual periodo?')
                                ->placeholder('Ex.: 2019 a 2021, 8 meses')
                                ->maxLength(255)
                                ->hidden(fn($get): bool => $get('esteve_unidade_prisional_ou_similar') !== 'sim')
                                ->required(fn($get): bool => $get('esteve_unidade_prisional_ou_similar') === 'sim')
                                ->dehydratedWhenHidden(),
                            Textarea::make('motivo_unidade_prisional')
                                ->label('Se sim, qual motivo?')
                                ->placeholder('Descreva de forma objetiva o motivo informado.')
                                ->rows(3)
                                ->hidden(fn($get): bool => $get('esteve_unidade_prisional_ou_similar') !== 'sim')
                                ->required(fn($get): bool => $get('esteve_unidade_prisional_ou_similar') === 'sim')
                                ->dehydratedWhenHidden(),
                            Radio::make('processos_judiciais_andamento')
                                ->label('Processos judiciais em andamento?')
                                ->boolean('Sim', 'Nao')
                                ->inline()
                                ->live()
                                ->default(false)
                                ->afterStateUpdated(function ($set, mixed $state): void {
                                    if ($state) {
                                        return;
                                    }

                                    $set('motivo_processos_judiciais_andamento', null);
                                }),
                            Textarea::make('motivo_processos_judiciais_andamento')
                                ->label('Se sim, qual o motivo?')
                                ->placeholder('Informe a natureza do processo ou a motivacao relatada.')
                                ->rows(3)
                                ->hidden(fn($get): bool => ! (bool) $get('processos_judiciais_andamento'))
                                ->required(fn($get): bool => (bool) $get('processos_judiciais_andamento'))
                                ->dehydratedWhenHidden(),
                            Radio::make('processos_judiciais_anteriores')
                                ->label('Processos judiciais anteriores?')
                                ->boolean('Sim', 'Nao')
                                ->inline()
                                ->live()
                                ->default(false)
                                ->afterStateUpdated(function ($set, mixed $state): void {
                                    if ($state) {
                                        return;
                                    }

                                    $set('motivo_processos_judiciais_anteriores', null);
                                }),
                            Textarea::make('motivo_processos_judiciais_anteriores')
                                ->label('Se sim, qual o motivo?')
                                ->placeholder('Registre o historico referido pelo acolhido.')
                                ->rows(3)
                                ->hidden(fn($get): bool => ! (bool) $get('processos_judiciais_anteriores'))
                                ->required(fn($get): bool => (bool) $get('processos_judiciais_anteriores'))
                                ->dehydratedWhenHidden(),
                        ]),
                    ]),
                Section::make('Repercussoes ocupacionais, familiares e clinicas')
                    ->description('Avalie os impactos funcionais e relacionais associados ao uso de substancias, incluindo desemprego e necessidade de internação hospitalar.')
                    ->icon('heroicon-o-briefcase')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            Radio::make('impactos_trabalho_uso_substancias')
                                ->label('Houve impactos no trabalho por causa do uso das substancias?')
                                ->options([
                                    'sim' => 'Sim',
                                    'nao' => 'Nao',
                                    'prefere_nao_informar' => 'Prefere nao informar',
                                ])
                                ->inline()
                                ->live()
                                ->required()
                                ->afterStateUpdated(function ($set, mixed $state): void {
                                    if ($state === 'sim') {
                                        return;
                                    }

                                    $set('detalhes_impactos_trabalho_uso_substancias', null);
                                })
                                ->columnSpanFull(),
                            Textarea::make('detalhes_impactos_trabalho_uso_substancias')
                                ->label('Se sim, especifique')
                                ->placeholder('Ex.: faltas, demissao, afastamento ou outros')
                                ->rows(3)
                                ->hidden(fn($get): bool => $get('impactos_trabalho_uso_substancias') !== 'sim')
                                ->required(fn($get): bool => $get('impactos_trabalho_uso_substancias') === 'sim')
                                ->dehydratedWhenHidden()
                                ->columnSpanFull(),
                            Radio::make('desempregado_por_uso_substancias')
                                ->label('Atualmente esta desempregado por causa do uso de substancias quimicas?')
                                ->boolean('Sim', 'Nao')
                                ->inline()
                                ->live()
                                ->default(false)
                                ->afterStateUpdated(function ($set, mixed $state): void {
                                    if ($state) {
                                        return;
                                    }

                                    $set('tempo_desemprego_por_uso_substancias', null);
                                }),
                            Select::make('tempo_desemprego_por_uso_substancias')
                                ->label('Se sim, ha quanto tempo?')
                                ->placeholder('Selecione a faixa de tempo')
                                ->options(self::unemploymentDurationOptions())
                                ->hidden(fn($get): bool => ! (bool) $get('desempregado_por_uso_substancias'))
                                ->required(fn($get): bool => (bool) $get('desempregado_por_uso_substancias'))
                                ->dehydratedWhenHidden(),
                            Radio::make('impacto_convivio_familiar_uso_substancias')
                                ->label('Houve impacto no convivio familiar devido ao uso de substancias quimicas?')
                                ->boolean('Sim', 'Nao')
                                ->inline()
                                ->live()
                                ->default(false)
                                ->afterStateUpdated(function ($set, mixed $state): void {
                                    if ($state) {
                                        return;
                                    }

                                    $set('detalhes_impacto_convivio_familiar', null);
                                    $set('frequencia_impacto_convivio_familiar', null);
                                }),
                            Textarea::make('detalhes_impacto_convivio_familiar')
                                ->label('Se sim, detalhe qual foi o impacto')
                                ->placeholder('Ex.: conflitos, afastamento, rompimento de vinculos, negligencia')
                                ->rows(3)
                                ->hidden(fn($get): bool => ! (bool) $get('impacto_convivio_familiar_uso_substancias'))
                                ->required(fn($get): bool => (bool) $get('impacto_convivio_familiar_uso_substancias'))
                                ->dehydratedWhenHidden()
                                ->columnSpanFull(),
                            Select::make('frequencia_impacto_convivio_familiar')
                                ->label('Quantas vezes ja teve esse impacto familiar?')
                                ->placeholder('Selecione a frequencia')
                                ->options(self::impactFrequencyOptions())
                                ->hidden(fn($get): bool => ! (bool) $get('impacto_convivio_familiar_uso_substancias'))
                                ->required(fn($get): bool => (bool) $get('impacto_convivio_familiar_uso_substancias'))
                                ->dehydratedWhenHidden(),
                            Radio::make('internacoes_hospitalares_uso_substancias')
                                ->label('Ja precisou de internacoes hospitalares devido ao uso de substancias quimicas?')
                                ->boolean('Sim', 'Nao')
                                ->inline()
                                ->live()
                                ->default(false)
                                ->afterStateUpdated(function ($set, mixed $state): void {
                                    if ($state) {
                                        return;
                                    }

                                    $set('quantidade_internacoes_hospitalares_uso_substancias', null);
                                    $set('detalhes_internacoes_hospitalares_uso_substancias', null);
                                }),
                            Select::make('quantidade_internacoes_hospitalares_uso_substancias')
                                ->label('Se sim, quantas vezes?')
                                ->placeholder('Selecione a frequencia')
                                ->options(self::impactFrequencyOptions())
                                ->hidden(fn($get): bool => ! (bool) $get('internacoes_hospitalares_uso_substancias'))
                                ->required(fn($get): bool => (bool) $get('internacoes_hospitalares_uso_substancias'))
                                ->dehydratedWhenHidden(),
                            Textarea::make('detalhes_internacoes_hospitalares_uso_substancias')
                                ->label('Detalhe como foi')
                                ->placeholder('Descreva contexto, gravidade, local e desfecho informado.')
                                ->rows(3)
                                ->hidden(fn($get): bool => ! (bool) $get('internacoes_hospitalares_uso_substancias'))
                                ->required(fn($get): bool => (bool) $get('internacoes_hospitalares_uso_substancias'))
                                ->dehydratedWhenHidden()
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Resumo do registro')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            TextEntry::make('acolhido.nome_completo_paciente')
                                ->label('Acolhido'),
                            TextEntry::make('nome')
                                ->label('Substancia'),
                            TextEntry::make('frequencia')
                                ->label('Frequencia')
                                ->placeholder('-'),
                            TextEntry::make('quantidade')
                                ->label('Quantidade')
                                ->placeholder('-'),
                            TextEntry::make('via_administracao')
                                ->label('Via de administracao')
                                ->placeholder('-'),
                            TextEntry::make('tempo_uso')
                                ->label('Tempo de uso')
                                ->placeholder('-'),
                            TextEntry::make('ultima_vez')
                                ->label('Ultima vez')
                                ->placeholder('-'),
                            IconEntry::make('houve_dependentes_quimicos_familia_convivencia')
                                ->label('Dependentes quimicos na familia')
                                ->boolean(),
                            TextEntry::make('nome_pessoa_dependente_familiar')
                                ->label('Nome da pessoa')
                                ->placeholder('-'),
                            TextEntry::make('influencia_terceiro_inicio_uso')
                                ->label('Influencia de terceiro')
                                ->formatStateUsing(fn(?string $state): string => match ($state) {
                                    'sim' => 'Sim',
                                    'nao' => 'Nao',
                                    'prefere_nao_informar' => 'Prefere nao informar',
                                    default => '-',
                                }),
                            TextEntry::make('tipo_relacao_influencia_terceiro')
                                ->label('Tipo de relacao')
                                ->placeholder('-'),
                            TextEntry::make('qual_grupo_apoio')
                                ->label('Grupo de apoio')
                                ->placeholder('-'),
                            TextEntry::make('quantas_internacoes_anteriores')
                                ->label('Internacoes anteriores')
                                ->placeholder('-'),
                            TextEntry::make('tempo_acolhimento_anterior')
                                ->label('Tempo de acolhimento anterior')
                                ->placeholder('-'),
                            TextEntry::make('esteve_unidade_prisional_ou_similar')
                                ->label('Unidade prisional ou similar')
                                ->formatStateUsing(fn(?string $state): string => match ($state) {
                                    'sim' => 'Sim',
                                    'nao' => 'Nao',
                                    'prefere_nao_informar' => 'Prefere nao informar',
                                    default => '-',
                                }),
                            TextEntry::make('impactos_trabalho_uso_substancias')
                                ->label('Impactos no trabalho')
                                ->formatStateUsing(fn(?string $state): string => match ($state) {
                                    'sim' => 'Sim',
                                    'nao' => 'Nao',
                                    'prefere_nao_informar' => 'Prefere nao informar',
                                    default => '-',
                                }),
                            TextEntry::make('tempo_desemprego_por_uso_substancias')
                                ->label('Tempo desempregado por uso')
                                ->placeholder('-'),
                            TextEntry::make('frequencia_impacto_convivio_familiar')
                                ->label('Frequencia do impacto familiar')
                                ->placeholder('-'),
                            TextEntry::make('quantidade_internacoes_hospitalares_uso_substancias')
                                ->label('Internacoes hospitalares por uso')
                                ->placeholder('-'),
                            TextEntry::make('observacoes')
                                ->label('Observacoes')
                                ->placeholder('-')
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nome')
            ->columns([
                TextColumn::make('acolhido.nome_completo_paciente')
                    ->label('Acolhido')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nome')
                    ->label('Substancia')
                    ->searchable()
                    ->wrap()
                    ->sortable(),
                TextColumn::make('frequencia')
                    ->label('Frequencia')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('via_administracao')
                    ->label('Via')
                    ->placeholder('-')
                    ->searchable(),
                IconColumn::make('houve_dependentes_quimicos_familia_convivencia')
                    ->label('Familia com dependencia')
                    ->boolean(),
                TextColumn::make('influencia_terceiro_inicio_uso')
                    ->label('Influencia de terceiro')
                    ->formatStateUsing(fn(?string $state): string => match ($state) {
                        'sim' => 'Sim',
                        'nao' => 'Nao',
                        'prefere_nao_informar' => 'Prefere nao informar',
                        default => '-',
                    }),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('downloadRelatorio')
                    ->label('Baixar relatorio')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(fn(SubstanciaPsicoativas $record) => static::downloadReportResponse($record)),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSubstanciaPsicoativas::route('/'),
            'view' => ViewSubstanciaPsicoativa::route('/{record}'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function getReportData(SubstanciaPsicoativas $record): array
    {
        $record->loadMissing('acolhido');

        $sections = [
            'Identificacao do registro' => [
                'Acolhido' => $record->acolhido?->nome_completo_paciente,
                'Substancias registradas' => $record->nome,
                'Atualizado em' => $record->updated_at,
            ],
            'Padrao de uso e consumo' => [
                'Frequencia de uso' => $record->frequencia,
                'Quantidade' => $record->quantidade,
                'Via de administracao' => $record->via_administracao,
                'Tempo de uso' => $record->tempo_uso,
                'Ultima vez que utilizou' => $record->ultima_vez,
                'Observacoes clinicas' => $record->observacoes,
            ],
            'Contexto familiar e inicio do uso' => [
                'Houve dependentes quimicos na familia' => $record->houve_dependentes_quimicos_familia_convivencia,
                'Nome da pessoa' => $record->nome_pessoa_dependente_familiar,
                'Influencia de terceiro no inicio do uso' => static::formatChoice($record->influencia_terceiro_inicio_uso),
                'Tipo de relacao' => $record->tipo_relacao_influencia_terceiro,
            ],
            'Historico terapeutico e acolhimento' => [
                'Participou de grupos de apoio' => $record->participou_grupos_apoio,
                'Qual grupo de apoio' => $record->qual_grupo_apoio,
                'Teve internacoes anteriores' => $record->teve_internacoes_anteriores,
                'Quantas internacoes anteriores' => $record->quantas_internacoes_anteriores,
                'Onde foram as internacoes' => $record->onde_internacoes_anteriores,
                'Quando ocorreram' => $record->quando_internacoes_anteriores,
                'Lembra o tempo de acolhimento anterior' => $record->lembra_tempo_acolhimento_anterior,
                'Tempo de acolhimento anterior' => $record->tempo_acolhimento_anterior,
            ],
            'Aspectos juridicos e institucionais' => [
                'Esteve em unidade prisional ou similar' => static::formatChoice($record->esteve_unidade_prisional_ou_similar),
                'Periodo em unidade prisional' => $record->periodo_unidade_prisional,
                'Motivo relacionado a unidade prisional' => $record->motivo_unidade_prisional,
                'Processos judiciais em andamento' => $record->processos_judiciais_andamento,
                'Motivo dos processos em andamento' => $record->motivo_processos_judiciais_andamento,
                'Processos judiciais anteriores' => $record->processos_judiciais_anteriores,
                'Motivo dos processos anteriores' => $record->motivo_processos_judiciais_anteriores,
            ],
            'Repercussoes ocupacionais, familiares e clinicas' => [
                'Impactos no trabalho pelo uso de substancias' => static::formatChoice($record->impactos_trabalho_uso_substancias),
                'Detalhes dos impactos no trabalho' => $record->detalhes_impactos_trabalho_uso_substancias,
                'Desempregado por uso de substancias' => $record->desempregado_por_uso_substancias,
                'Tempo de desemprego relacionado ao uso' => static::formatChoice($record->tempo_desemprego_por_uso_substancias),
                'Impacto no convivio familiar' => $record->impacto_convivio_familiar_uso_substancias,
                'Detalhes do impacto familiar' => $record->detalhes_impacto_convivio_familiar,
                'Frequencia do impacto familiar' => static::formatChoice($record->frequencia_impacto_convivio_familiar),
                'Internacoes hospitalares devido ao uso' => $record->internacoes_hospitalares_uso_substancias,
                'Quantidade de internacoes hospitalares' => static::formatChoice($record->quantidade_internacoes_hospitalares_uso_substancias),
                'Detalhes das internacoes hospitalares' => $record->detalhes_internacoes_hospitalares_uso_substancias,
            ],
        ];

        return [
            'record' => $record,
            'sections' => $sections,
            'logoCerape' => static::publicImageDataUri('storage/images/logo.png'),
            'formatValue' => fn(mixed $value): string => static::formatValue($value),
        ];
    }

    public static function downloadReportResponse(SubstanciaPsicoativas $record)
    {
        $record->loadMissing('acolhido');

        $pdf = Pdf::loadView('pdf.substancia-psicoativa-report', static::getReportData($record))
            ->setPaper('a4');

        $fileName = 'relatorio-substancia-psicoativa-' . Str::slug($record->acolhido?->nome_completo_paciente ?? 'acolhido') . '.pdf';

        return response()->streamDownload(
            fn() => print($pdf->output()),
            $fileName,
            ['Content-Type' => 'application/pdf'],
        );
    }

    public static function notifyUsers(SubstanciaPsicoativas $record, string $event): void
    {
        $users = User::query()->get();

        if ($users->isEmpty()) {
            return;
        }

        $record->loadMissing('acolhido');

        $notification = Notification::make()
            ->title(self::notificationTitle($event))
            ->body(self::notificationBody($record, $event))
            ->icon(self::notificationIcon($event))
            ->viewData([
                'key' => self::notificationKey($record, $event),
            ]);

        match ($event) {
            'created' => $notification->success(),
            'deleted' => $notification->danger(),
            default => $notification->info(),
        };

        FilamentDatabaseNotifications::send($notification, $users);
    }

    private static function notificationTitle(string $event): string
    {
        return match ($event) {
            'created' => 'Substancia psicoativa cadastrada',
            'updated' => 'Substancia psicoativa atualizada',
            'deleted' => 'Substancia psicoativa removida',
            default => 'Substancia psicoativa atualizada',
        };
    }

    private static function notificationBody(SubstanciaPsicoativas $record, string $event): string
    {
        $acolhido = $record->acolhido?->nome_completo_paciente ?? 'acolhido nao identificado';

        $action = match ($event) {
            'created' => 'foi cadastrada',
            'updated' => 'foi atualizada',
            'deleted' => 'foi removida',
            default => 'foi atualizada',
        };

        return "{$record->nome} {$action} para {$acolhido}.";
    }

    private static function notificationIcon(string $event): string
    {
        return match ($event) {
            'created' => 'heroicon-o-plus-circle',
            'updated' => 'heroicon-o-pencil-square',
            'deleted' => 'heroicon-o-trash',
            default => 'heroicon-o-bell',
        };
    }

    private static function notificationKey(SubstanciaPsicoativas $record, string $event): string
    {
        return "substancia_psicoativa_{$event}_{$record->getKey()}_" . ($record->updated_at?->timestamp ?? now()->timestamp);
    }

    private static function formatValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'Sim' : 'Nao';
        }

        if ($value instanceof \Carbon\CarbonInterface) {
            if ($value->format('H:i:s') === '00:00:00') {
                return $value->format('d/m/Y');
            }

            return $value->format('d/m/Y H:i');
        }

        if (is_array($value)) {
            return blank($value) ? '-' : implode(', ', array_filter($value));
        }

        $value = trim(strip_tags((string) $value));

        return $value !== '' ? $value : '-';
    }

    private static function formatChoice(?string $value): ?string
    {
        return match ($value) {
            'sim' => 'Sim',
            'nao' => 'Nao',
            'prefere_nao_informar' => 'Prefere nao informar',
            '1_vez' => '1 vez',
            '2_a_3_vezes' => '2 a 3 vezes',
            '4_a_10_vezes' => '4 a 10 vezes',
            'mais_de_10_vezes' => 'Mais de 10 vezes',
            '1_ate_3_anos' => 'De 1 ate 3 anos',
            '3_ate_10_anos' => 'De 3 ate 10 anos',
            'mais_de_10_anos' => 'Mais de 10 anos',
            default => $value,
        };
    }

    private static function impactFrequencyOptions(): array
    {
        return [
            '1_vez' => '1 vez',
            '2_a_3_vezes' => '2 a 3 vezes',
            '4_a_10_vezes' => '4 a 10 vezes',
            'mais_de_10_vezes' => 'Mais de 10 vezes',
        ];
    }

    private static function unemploymentDurationOptions(): array
    {
        return [
            '1_ate_3_anos' => 'De 1 ate 3 anos',
            '3_ate_10_anos' => 'De 3 ate 10 anos',
            'mais_de_10_anos' => 'Mais de 10 anos',
        ];
    }

    private static function publicImageDataUri(string $relativePath): ?string
    {
        $absolutePath = public_path($relativePath);

        if (! is_file($absolutePath)) {
            return null;
        }

        $mimeType = mime_content_type($absolutePath) ?: 'image/png';

        return 'data:' . $mimeType . ';base64,' . base64_encode((string) file_get_contents($absolutePath));
    }
}
