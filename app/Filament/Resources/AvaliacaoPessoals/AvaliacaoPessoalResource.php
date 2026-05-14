<?php

namespace App\Filament\Resources\AvaliacaoPessoals;

use App\Filament\Resources\AvaliacaoPessoals\Pages\AutoAvaliacao;
use App\Filament\Resources\AvaliacaoPessoals\Pages\CreateAvaliacaoPessoal;
use App\Filament\Resources\AvaliacaoPessoals\Pages\EditAvaliacaoPessoal;
use App\Filament\Resources\AvaliacaoPessoals\Pages\ListAvaliacaoPessoals;
use App\Filament\Resources\AvaliacaoPessoals\Pages\RelatorioAvaliacaoPessoal;
use App\Filament\Resources\AvaliacaoPessoals\Pages\ViewAvaliacaoPessoal;
use App\Models\Acolhido;
use App\Models\AvaliacaoPessoal;
use App\Models\User;
use App\Support\FilamentDatabaseNotifications;
use BackedEnum;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class AvaliacaoPessoalResource extends Resource
{
    protected static ?string $model = AvaliacaoPessoal::class;

    protected static string | UnitEnum | null $navigationGroup = 'Avaliaçoes';

    protected static ?string $navigationLabel = 'Avaliacoes pessoais';

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $slug = 'avaliacoes-pessoais';

    protected static ?string $modelLabel = 'avaliacao pessoal';

    protected static ?string $pluralModelLabel = 'avaliacoes pessoais';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificacao da avaliacao')
                    ->description('Relacione a avaliacao ao acolhido e ao profissional responsavel.')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 3,
                        ])->schema([
                            Select::make('acolhido_id')
                                ->label('Acolhido')
                                ->relationship('acolhido', 'nome_completo_paciente')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(
                                    fn(Set $set, mixed $state): mixed => $set('dias_na_casa', self::calculateDiasNaCasa($state))
                                )
                                ->required(),
                            Select::make('user_id')
                                ->label('Usuario avaliador')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->preload()
                                ->default(auth()->id())
                                ->required(),
                            TextInput::make('dias_na_casa')
                                ->label('Tempo de casa')
                                ->maxLength(255)
                                ->readOnly()
                                ->dehydrated()
                                ->helperText('Calculado automaticamente pela data de cadastro do acolhido.')
                                ->required(),
                        ]),
                    ]),
                Section::make('Pontuaçao')
                    ->description('Cada criterio aceita apenas notas maiores que 1 e menores ou iguais a 3. A media final e calculada automaticamente.')
                    ->icon('heroicon-o-star')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 3,
                        ])->schema([
                            self::scoreInput('controler', 'Controle'),
                            self::scoreInput('autonomia', 'Autonomia'),
                            self::scoreInput('transparencia', 'Transparencia'),
                            self::scoreInput('superacao', 'Superacao'),
                            self::scoreInput('autocuidado', 'Autocuidado'),
                            TextInput::make('Total')
                                ->label('Media final')
                                ->numeric()
                                ->readOnly()
                                ->dehydrated()
                                ->default(0)
                                ->minValue(0)
                                ->maxValue(3)
                                ->suffix('/ 3'),
                        ]),
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Resumo')
                    ->columns([
                        'default' => 1,
                        'md' => 3,
                    ])
                    ->schema([
                        ImageEntry::make('acolhido.avatar')
                            ->label('Foto do acolhido')
                            ->disk('public')
                            ->circular()
                            ->height(96)
                            ->width(96)
                            ->getStateUsing(fn(AvaliacaoPessoal $record): ?string => self::resolveAvatarPath($record->acolhido?->avatar)),
                        TextEntry::make('acolhido.nome_completo_paciente')
                            ->label('Acolhido'),
                        TextEntry::make('user.name')
                            ->label('Usuario avaliador')
                            ->placeholder('-'),
                        TextEntry::make('dias_na_casa')
                            ->label('Tempo de casa'),
                        TextEntry::make('controler')
                            ->label('Controle')
                            ->suffix(' / 3'),
                        TextEntry::make('autonomia')
                            ->label('Autonomia')
                            ->suffix(' / 3'),
                        TextEntry::make('transparencia')
                            ->label('Transparencia')
                            ->suffix(' / 3'),
                        TextEntry::make('superacao')
                            ->label('Superacao')
                            ->suffix(' / 3'),
                        TextEntry::make('autocuidado')
                            ->label('Autocuidado')
                            ->suffix(' / 3'),
                        TextEntry::make('Total')
                            ->label('Media final')
                            ->badge()
                            ->color(fn($state): string => self::scoreColor((float) $state))
                            ->suffix(' / 3'),
                        TextEntry::make('media_de_todos')
                            ->label('Media de todos')
                            ->badge()
                            ->color(fn($state): string => self::scoreColor((float) $state))
                            ->getStateUsing(fn(AvaliacaoPessoal $record): float => self::calculateMediaDeTodos($record->acolhido_id))
                            ->formatStateUsing(fn($state): string => self::formatScore((float) $state)),
                        TextEntry::make('total_avaliadores')
                            ->label('Usuarios que avaliaram')
                            ->badge()
                            ->color('primary')
                            ->getStateUsing(fn(AvaliacaoPessoal $record): int => self::countEvaluators($record->acolhido_id)),
                    ]),
                Section::make('Analise por usuario')
                    ->description('Resumo consolidado das avaliacoes feitas por cada usuario para este acolhido.')
                    ->icon('heroicon-o-users')
                    ->schema([
                        ViewEntry::make('analise_usuarios')
                            ->hiddenLabel()
                            ->view('filament.resources.avaliacao-pessoals.user-analysis')
                            ->viewData(fn(AvaliacaoPessoal $record): array => self::getReportData($record)),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query): Builder => $query->with(['acolhido', 'user']))
            ->columns([
                TextColumn::make('acolhido.nome_completo_paciente')
                    ->label('Acolhido')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Usuario avaliador')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('dias_na_casa')
                    ->label('Tempo de casa')
                    ->searchable(),
                TextColumn::make('Total')
                    ->label('Media')
                    ->badge()
                    ->color(fn($state): string => match (true) {
                        (float) $state >= 2.5 => 'success',
                        (float) $state >= 1.5 => 'warning',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn($state): string => number_format((float) $state, 2, ',', '.') . ' / 3')
                    ->sortable(),
                TextColumn::make('media_de_todos')
                    ->label('Media de todos')
                    ->badge()
                    ->color(fn($state): string => self::scoreColor((float) $state))
                    ->getStateUsing(
                        fn(AvaliacaoPessoal $record): float => self::calculateMediaDeTodos($record->acolhido_id)
                    )
                    ->formatStateUsing(fn($state): string => self::formatScore((float) $state)),
                TextColumn::make('created_at')
                    ->label('Avaliado em')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('acolhido_id')
                    ->label('Acolhido')
                    ->relationship('acolhido', 'nome_completo_paciente')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('user_id')
                    ->label('Usuario avaliador')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAvaliacaoPessoals::route('/'),
            'auto-avaliacao' => AutoAvaliacao::route('/auto-avaliacao'),
            'create' => CreateAvaliacaoPessoal::route('/create'),
            'view' => ViewAvaliacaoPessoal::route('/{record}'),
            'report' => RelatorioAvaliacaoPessoal::route('/{record}/relatorio'),
            'edit' => EditAvaliacaoPessoal::route('/{record}/edit'),
        ];
    }

    private static function scoreInput(string $name, string $label): TextInput
    {
        return TextInput::make($name)
            ->label($label)
            ->numeric()
            ->step(0.01)
            ->minValue(1.01)
            ->maxValue(3)
            ->default(null)
            ->required()
            ->helperText('Informe uma nota maior que 1 e menor ou igual a 3.')
            ->validationMessages([
                'required' => 'Preencha a nota de ' . mb_strtolower($label) . '.',
                'numeric' => 'A nota de ' . mb_strtolower($label) . ' deve ser um numero.',
                'min' => 'A nota de ' . mb_strtolower($label) . ' deve ser maior que 1.',
                'max' => 'A nota de ' . mb_strtolower($label) . ' nao pode ser maior que 3.',
            ])
            ->live(onBlur: true)
            ->afterStateUpdated(fn(Get $get, Set $set): mixed => self::refreshTotal($get, $set))
            ->suffix('/ 3');
    }

    private static function refreshTotal(Get $get, Set $set): void
    {
        $data = [];

        foreach (self::scoreFields() as $field) {
            $data[$field] = $get($field);
        }

        $set('Total', number_format(self::calculateTotal($data), 2, '.', ''));
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function calculateTotal(array $data): float
    {
        return (float) collect(self::scoreFields())
            ->map(fn(string $field): float => min(3, max(0, (float) ($data[$field] ?? 0))))
            ->avg();
    }

    public static function calculateDiasNaCasa(mixed $acolhidoId): ?string
    {
        if (blank($acolhidoId)) {
            return null;
        }

        $acolhido = Acolhido::query()->find($acolhidoId);

        if (! $acolhido?->created_at) {
            return null;
        }

        $days = $acolhido->created_at->startOfDay()->diffInDays(now()->startOfDay());

        return match (true) {
            $days === 0 => 'Cadastrado hoje',
            $days === 1 => '1 dia de casa',
            default => "{$days} dias de casa",
        };
    }

    public static function calculateMediaDeTodos(int $acolhidoId): float
    {
        $userAverages = AvaliacaoPessoal::query()
            ->where('acolhido_id', $acolhidoId)
            ->whereNotNull('user_id')
            ->selectRaw('user_id, AVG(`Total`) as media_usuario')
            ->groupBy('user_id')
            ->pluck('media_usuario');

        if ($userAverages->isEmpty()) {
            return 0;
        }

        return (float) $userAverages->avg();
    }

    public static function countEvaluators(int $acolhidoId): int
    {
        return AvaliacaoPessoal::query()
            ->where('acolhido_id', $acolhidoId)
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');
    }

    /**
     * @return array<string, mixed>
     */
    public static function getReportData(AvaliacaoPessoal $record): array
    {
        $record->loadMissing(['acolhido', 'user']);

        $avaliacoes = AvaliacaoPessoal::query()
            ->with(['user', 'acolhido'])
            ->where('acolhido_id', $record->acolhido_id)
            ->latest()
            ->get();

        $usuarios = self::summarizeEvaluators($avaliacoes);
        $criteriaAverages = self::calculateCriteriaAverages($avaliacoes);
        $personalData = self::buildAcolhidoPersonalData($record->acolhido);
        $periodComparisons = [
            'semanal' => self::calculatePeriodComparison($record->acolhido_id, 'semanal'),
            'mensal' => self::calculatePeriodComparison($record->acolhido_id, 'mensal'),
            'semestral' => self::calculatePeriodComparison($record->acolhido_id, 'semestral'),
            'anual' => self::calculatePeriodComparison($record->acolhido_id, 'anual'),
        ];
        $somaMediasIndividuais = min(3, (float) $usuarios->sum('media'));
        $logicasMedias = [
            [
                'titulo' => 'Logica da media individual de um avaliador',
                'descricao' => 'Para cada avaliador, a media individual e calculada pela soma das medias finais registradas por esse avaliador para o acolhido, dividida pela quantidade de avaliacoes que ele realizou.',
                'formula' => 'Media individual = soma das medias finais do avaliador / quantidade de avaliacoes do avaliador',
            ],
            [
                'titulo' => 'Logica da media de todos os avaliadores',
                'descricao' => 'A media de todos considera a media individual de cada avaliador com o mesmo peso. Primeiro calculamos a media individual de cada profissional. Depois calculamos a media dessas medias individuais.',
                'formula' => 'Media de todos = soma das medias individuais dos avaliadores / quantidade de avaliadores',
            ],
            [
                'titulo' => 'Regra de apresentacao da soma das medias individuais',
                'descricao' => 'Nos relatorios, a soma das medias individuais exibida em tela e no PDF nunca ultrapassa 3, respeitando a escala maxima da avaliacao pessoal.',
                'formula' => 'Soma exibida = menor valor entre 3 e a soma das medias individuais',
            ],
        ];

        return [
            'record' => $record,
            'acolhido' => $record->acolhido,
            'avaliacoes' => $avaliacoes,
            'usuarios' => $usuarios,
            'personalData' => $personalData,
            'criteriaAverages' => $criteriaAverages,
            'mediaDeTodos' => self::calculateMediaDeTodos($record->acolhido_id),
            'somaMediasIndividuais' => $somaMediasIndividuais,
            'logicasMedias' => $logicasMedias,
            'totalAvaliadores' => self::countEvaluators($record->acolhido_id),
            'totalAvaliacoes' => $avaliacoes->count(),
            'ultimaAvaliacao' => $avaliacoes->first(),
            'primeiraAvaliacao' => $avaliacoes->last(),
            'periodComparisons' => $periodComparisons,
            'fotoAcolhido' => self::imageDataUri($record->acolhido?->avatar),
            'logoCerape' => self::publicImageDataUri('storage/images/logo.png'),
            'formatScore' => fn(float $score): string => self::formatScore($score),
            'scoreColor' => fn(float $score): string => self::scoreColor($score),
        ];
    }

    /**
     * @param  Collection<int, AvaliacaoPessoal>  $avaliacoes
     * @return Collection<int, array<string, mixed>>
     */
    protected static function summarizeEvaluators(Collection $avaliacoes): Collection
    {
        return $avaliacoes
            ->filter(fn(AvaliacaoPessoal $avaliacao): bool => filled($avaliacao->user_id))
            ->groupBy('user_id')
            ->map(function ($avaliacoesDoUsuario) {
                /** @var \Illuminate\Support\Collection<int, AvaliacaoPessoal> $avaliacoesDoUsuario */
                $primeiraAvaliacao = $avaliacoesDoUsuario->first();
                $avaliacoesOrdenadas = $avaliacoesDoUsuario->sortByDesc('created_at')->values();

                return [
                    'user' => $primeiraAvaliacao?->user,
                    'foto' => self::imageDataUri($primeiraAvaliacao?->user?->avatar),
                    'quantidade' => $avaliacoesDoUsuario->count(),
                    'media' => (float) $avaliacoesDoUsuario->avg('Total'),
                    'ultima_avaliacao' => $avaliacoesOrdenadas->first(),
                    'criterios' => [
                        'Controle' => (float) $avaliacoesDoUsuario->avg('controler'),
                        'Autonomia' => (float) $avaliacoesDoUsuario->avg('autonomia'),
                        'Transparencia' => (float) $avaliacoesDoUsuario->avg('transparencia'),
                        'Superacao' => (float) $avaliacoesDoUsuario->avg('superacao'),
                        'Autocuidado' => (float) $avaliacoesDoUsuario->avg('autocuidado'),
                    ],
                    'avaliacoes' => $avaliacoesOrdenadas,
                ];
            })
            ->sortByDesc('media')
            ->values();
    }

    /**
     * @param  Collection<int, AvaliacaoPessoal>  $avaliacoes
     * @return array<string, float>
     */
    protected static function calculateCriteriaAverages(Collection $avaliacoes): array
    {
        return [
            'Controle' => (float) $avaliacoes->avg('controler'),
            'Autonomia' => (float) $avaliacoes->avg('autonomia'),
            'Transparencia' => (float) $avaliacoes->avg('transparencia'),
            'Superacao' => (float) $avaliacoes->avg('superacao'),
            'Autocuidado' => (float) $avaliacoes->avg('autocuidado'),
        ];
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    protected static function buildAcolhidoPersonalData(?Acolhido $acolhido): array
    {
        if (! $acolhido) {
            return [];
        }

        $items = [
            ['label' => 'Nome completo', 'value' => (string) ($acolhido->nome_completo_paciente ?? '-')],
            ['label' => 'Data de nascimento', 'value' => $acolhido->data_nascimento?->format('d/m/Y') ?? '-'],
            ['label' => 'Idade', 'value' => $acolhido->data_nascimento?->age ? $acolhido->data_nascimento->age . ' anos' : '-'],
            ['label' => 'Estado civil', 'value' => (string) ($acolhido->estado_civil ?? '-')],
            ['label' => 'Escolaridade', 'value' => (string) ($acolhido->escolaridade ?? '-')],
            ['label' => 'Profissao', 'value' => (string) ($acolhido->profissao ?? '-')],
            ['label' => 'Telefone', 'value' => (string) ($acolhido->numero_do_telefone ?? '-')],
            ['label' => 'Municipio / UF', 'value' => trim(((string) ($acolhido->municipio_do_paciente ?? '')) . ' / ' . ((string) ($acolhido->uf_municipio_do_paciente ?? '')), ' /') ?: '-'],
            ['label' => 'Endereco', 'value' => (string) ($acolhido->endereco_paciente ?? '-')],
            ['label' => 'Responsavel pela intervencao', 'value' => (string) ($acolhido->responsavel_pela_intervencao_do_acolhido ?? '-')],
            ['label' => 'Profissional de referencia', 'value' => (string) ($acolhido->profissional_referencia_acolhido_instituicao ?? '-')],
            ['label' => 'Data de cadastro', 'value' => $acolhido->created_at?->format('d/m/Y H:i') ?? '-'],
        ];

        return array_values(array_filter(
            $items,
            fn(array $item): bool => filled($item['value']) && $item['value'] !== '- / '
        ));
    }

    public static function calculateRawAverageForRange(int $acolhidoId, Carbon $start, Carbon $end): float
    {
        return (float) (AvaliacaoPessoal::query()
            ->where('acolhido_id', $acolhidoId)
            ->whereBetween('created_at', [$start, $end])
            ->avg('Total') ?? 0);
    }

    public static function calculateConsolidatedAverageForRange(int $acolhidoId, Carbon $start, Carbon $end): float
    {
        $userAverages = AvaliacaoPessoal::query()
            ->where('acolhido_id', $acolhidoId)
            ->whereNotNull('user_id')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('user_id, AVG(`Total`) as media_usuario')
            ->groupBy('user_id')
            ->pluck('media_usuario');

        if ($userAverages->isEmpty()) {
            return 0;
        }

        return (float) $userAverages->avg();
    }

    /**
     * @return array<string, Carbon>
     */
    protected static function getPeriodDateRanges(string $period): array
    {
        $now = now();

        return match ($period) {
            'mensal' => [
                'current_start' => $now->copy()->startOfMonth(),
                'current_end' => $now->copy()->endOfMonth(),
                'previous_start' => $now->copy()->subMonthNoOverflow()->startOfMonth(),
                'previous_end' => $now->copy()->subMonthNoOverflow()->endOfMonth(),
            ],
            'semestral' => [
                'current_start' => $now->copy()->subMonths(5)->startOfMonth(),
                'current_end' => $now->copy()->endOfMonth(),
                'previous_start' => $now->copy()->subMonths(11)->startOfMonth(),
                'previous_end' => $now->copy()->subMonths(6)->endOfMonth(),
            ],
            'anual' => [
                'current_start' => $now->copy()->subMonths(11)->startOfMonth(),
                'current_end' => $now->copy()->endOfMonth(),
                'previous_start' => $now->copy()->subMonths(23)->startOfMonth(),
                'previous_end' => $now->copy()->subMonths(12)->endOfMonth(),
            ],
            default => [
                'current_start' => $now->copy()->subDays(6)->startOfDay(),
                'current_end' => $now->copy()->endOfDay(),
                'previous_start' => $now->copy()->subDays(13)->startOfDay(),
                'previous_end' => $now->copy()->subDays(7)->endOfDay(),
            ],
        };
    }

    /**
     * @return array<string, mixed>
     */
    public static function calculatePeriodComparison(int $acolhidoId, string $period): array
    {
        $ranges = self::getPeriodDateRanges($period);
        $currentRaw = self::calculateRawAverageForRange($acolhidoId, $ranges['current_start'], $ranges['current_end']);
        $previousRaw = self::calculateRawAverageForRange($acolhidoId, $ranges['previous_start'], $ranges['previous_end']);
        $currentConsolidated = self::calculateConsolidatedAverageForRange($acolhidoId, $ranges['current_start'], $ranges['current_end']);
        $previousConsolidated = self::calculateConsolidatedAverageForRange($acolhidoId, $ranges['previous_start'], $ranges['previous_end']);

        return [
            'period' => $period,
            'label' => match ($period) {
                'mensal' => 'Comparativo mensal',
                'semestral' => 'Comparativo semestral',
                'anual' => 'Comparativo anual',
                default => 'Comparativo semanal',
            },
            'current_label' => self::formatRangeLabel($ranges['current_start'], $ranges['current_end']),
            'previous_label' => self::formatRangeLabel($ranges['previous_start'], $ranges['previous_end']),
            'raw_current' => $currentRaw,
            'raw_previous' => $previousRaw,
            'raw_delta' => $currentRaw - $previousRaw,
            'consolidated_current' => $currentConsolidated,
            'consolidated_previous' => $previousConsolidated,
            'consolidated_delta' => $currentConsolidated - $previousConsolidated,
            'current_total_evaluations' => AvaliacaoPessoal::query()
                ->where('acolhido_id', $acolhidoId)
                ->whereBetween('created_at', [$ranges['current_start'], $ranges['current_end']])
                ->count(),
            'previous_total_evaluations' => AvaliacaoPessoal::query()
                ->where('acolhido_id', $acolhidoId)
                ->whereBetween('created_at', [$ranges['previous_start'], $ranges['previous_end']])
                ->count(),
        ];
    }

    protected static function formatRangeLabel(Carbon $start, Carbon $end): string
    {
        return $start->format('d/m/Y') . ' a ' . $end->format('d/m/Y');
    }

    public static function notifyUsersAboutEvaluation(AvaliacaoPessoal $avaliacao): void
    {
        $avaliacao->loadMissing('acolhido');

        if (! $avaliacao->acolhido) {
            return;
        }

        $evaluatedUserIds = AvaliacaoPessoal::query()
            ->where('acolhido_id', $avaliacao->acolhido_id)
            ->whereNotNull('user_id')
            ->distinct()
            ->pluck('user_id');

        $usersPendingEvaluation = User::query()
            ->whereNotIn('id', $evaluatedUserIds)
            ->get();

        $usersWhoEvaluated = User::query()
            ->whereIn('id', $evaluatedUserIds)
            ->get();

        if ($usersPendingEvaluation->isNotEmpty()) {
            FilamentDatabaseNotifications::send(
                Notification::make()
                    ->title('Avaliacao pendente')
                    ->body("O acolhido {$avaliacao->acolhido->nome_completo_paciente} ainda precisa da sua avaliacao.")
                    ->warning()
                    ->icon('heroicon-o-clipboard-document-check'),
                $usersPendingEvaluation,
            );
        }

        if ($usersWhoEvaluated->isNotEmpty()) {
            FilamentDatabaseNotifications::send(
                Notification::make()
                    ->title('Media de avaliacao atualizada')
                    ->body("O acolhido {$avaliacao->acolhido->nome_completo_paciente} ja possui media de todos: " . self::formatScore(self::calculateMediaDeTodos($avaliacao->acolhido_id)) . '.')
                    ->success()
                    ->icon('heroicon-o-chart-bar'),
                $usersWhoEvaluated,
            );
        }
    }

    /**
     * @return array<string, mixed>
     */
    public static function getAutoEvaluationReportData(): array
    {
        $acolhidos = Acolhido::query()
            ->orderBy('nome_completo_paciente')
            ->get()
            ->map(function (Acolhido $acolhido): array {
                $dias = $acolhido->created_at?->startOfDay()->diffInDays(now()->startOfDay()) ?? 0;

                return [
                    'matricula' => $acolhido->getKey(),
                    'nome' => $acolhido->nome_completo_paciente ?? '-',
                    'dias_na_casa' => $dias,
                ];
            });

        return [
            'acolhidos' => $acolhidos,
            'logoCerape' => self::publicImageDataUri('storage/images/logo.png'),
            'geradoEm' => now(),
        ];
    }

    public static function scoreColor(float $score): string
    {
        return match (true) {
            $score >= 2.5 => 'success',
            $score >= 1.5 => 'warning',
            $score > 0 => 'danger',
            default => 'gray',
        };
    }

    public static function formatScore(float $score): string
    {
        return number_format($score, 2, ',', '.') . ' / 3';
    }

    public static function resolveAvatarPath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $disk = Storage::disk('public');

        foreach (
            array_unique([
                $path,
                'acolhidos/avatars/' . basename($path),
                'users/avatars/' . basename($path),
                'avatars/' . basename($path),
            ]) as $candidate
        ) {
            if ($disk->exists($candidate)) {
                return $candidate;
            }
        }

        return $path;
    }

    public static function imageDataUri(?string $path): ?string
    {
        $path = self::resolveAvatarPath($path);

        if (blank($path) || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $absolutePath = Storage::disk('public')->path($path);
        $mimeType = mime_content_type($absolutePath) ?: 'image/jpeg';

        return 'data:' . $mimeType . ';base64,' . base64_encode((string) file_get_contents($absolutePath));
    }

    public static function publicImageDataUri(string $relativePath): ?string
    {
        $absolutePath = public_path($relativePath);

        if (! is_file($absolutePath)) {
            return null;
        }

        $mimeType = mime_content_type($absolutePath) ?: 'image/png';

        return 'data:' . $mimeType . ';base64,' . base64_encode((string) file_get_contents($absolutePath));
    }

    /**
     * @return array<int, string>
     */
    private static function scoreFields(): array
    {
        return [
            'controler',
            'autonomia',
            'transparencia',
            'superacao',
            'autocuidado',
        ];
    }
}
