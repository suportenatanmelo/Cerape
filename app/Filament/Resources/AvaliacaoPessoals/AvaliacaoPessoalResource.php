<?php

namespace App\Filament\Resources\AvaliacaoPessoals;

use App\Filament\Resources\AvaliacaoPessoals\Pages\CreateAvaliacaoPessoal;
use App\Filament\Resources\AvaliacaoPessoals\Pages\EditAvaliacaoPessoal;
use App\Filament\Resources\AvaliacaoPessoals\Pages\ListAvaliacaoPessoals;
use App\Filament\Resources\AvaliacaoPessoals\Pages\ViewAvaliacaoPessoal;
use App\Models\Acolhido;
use App\Models\AvaliacaoPessoal;
use App\Models\User;
use BackedEnum;
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
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class AvaliacaoPessoalResource extends Resource
{
    protected static ?string $model = AvaliacaoPessoal::class;

    protected static string | UnitEnum | null $navigationGroup = 'Avaliacoes';

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
                                    fn (Set $set, mixed $state): mixed => $set('dias_na_casa', self::calculateDiasNaCasa($state))
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
                Section::make('Pontuacao')
                    ->description('Cada criterio aceita notas de 0 a 3. A media final e calculada automaticamente.')
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
                            ->getStateUsing(fn (AvaliacaoPessoal $record): ?string => self::resolveAvatarPath($record->acolhido?->avatar)),
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
                            ->color(fn ($state): string => self::scoreColor((float) $state))
                            ->suffix(' / 3'),
                        TextEntry::make('media_de_todos')
                            ->label('Media de todos')
                            ->badge()
                            ->color(fn ($state): string => self::scoreColor((float) $state))
                            ->getStateUsing(fn (AvaliacaoPessoal $record): float => self::calculateMediaDeTodos($record->acolhido_id))
                            ->formatStateUsing(fn ($state): string => self::formatScore((float) $state)),
                        TextEntry::make('total_avaliadores')
                            ->label('Usuarios que avaliaram')
                            ->badge()
                            ->color('primary')
                            ->getStateUsing(fn (AvaliacaoPessoal $record): int => self::countEvaluators($record->acolhido_id)),
                    ]),
                Section::make('Analise por usuario')
                    ->description('Resumo consolidado das avaliacoes feitas por cada usuario para este acolhido.')
                    ->icon('heroicon-o-users')
                    ->schema([
                        ViewEntry::make('analise_usuarios')
                            ->hiddenLabel()
                            ->view('filament.resources.avaliacao-pessoals.user-analysis')
                            ->viewData(fn (AvaliacaoPessoal $record): array => self::getReportData($record)),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['acolhido', 'user']))
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
                    ->color(fn ($state): string => match (true) {
                        (float) $state >= 2.5 => 'success',
                        (float) $state >= 1.5 => 'warning',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 2, ',', '.') . ' / 3')
                    ->sortable(),
                TextColumn::make('media_de_todos')
                    ->label('Media de todos')
                    ->badge()
                    ->color(fn ($state): string => self::scoreColor((float) $state))
                    ->getStateUsing(
                        fn (AvaliacaoPessoal $record): float => self::calculateMediaDeTodos($record->acolhido_id)
                    )
                    ->formatStateUsing(fn ($state): string => self::formatScore((float) $state)),
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
            'create' => CreateAvaliacaoPessoal::route('/create'),
            'view' => ViewAvaliacaoPessoal::route('/{record}'),
            'edit' => EditAvaliacaoPessoal::route('/{record}/edit'),
        ];
    }

    private static function scoreInput(string $name, string $label): TextInput
    {
        return TextInput::make($name)
            ->label($label)
            ->numeric()
            ->step(0.01)
            ->minValue(0)
            ->maxValue(3)
            ->default(0)
            ->required()
            ->live(onBlur: true)
            ->afterStateUpdated(fn (Get $get, Set $set): mixed => self::refreshTotal($get, $set))
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
            ->map(fn (string $field): float => min(3, max(0, (float) ($data[$field] ?? 0))))
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

        $usuarios = $avaliacoes
            ->filter(fn (AvaliacaoPessoal $avaliacao): bool => filled($avaliacao->user_id))
            ->groupBy('user_id')
            ->map(function ($avaliacoesDoUsuario) {
                /** @var \Illuminate\Support\Collection<int, AvaliacaoPessoal> $avaliacoesDoUsuario */
                $primeiraAvaliacao = $avaliacoesDoUsuario->first();

                return [
                    'user' => $primeiraAvaliacao?->user,
                    'foto' => self::imageDataUri($primeiraAvaliacao?->user?->avatar),
                    'quantidade' => $avaliacoesDoUsuario->count(),
                    'media' => (float) $avaliacoesDoUsuario->avg('Total'),
                    'ultima_avaliacao' => $avaliacoesDoUsuario->sortByDesc('created_at')->first(),
                    'criterios' => [
                        'Controle' => (float) $avaliacoesDoUsuario->avg('controler'),
                        'Autonomia' => (float) $avaliacoesDoUsuario->avg('autonomia'),
                        'Transparencia' => (float) $avaliacoesDoUsuario->avg('transparencia'),
                        'Superacao' => (float) $avaliacoesDoUsuario->avg('superacao'),
                        'Autocuidado' => (float) $avaliacoesDoUsuario->avg('autocuidado'),
                    ],
                ];
            })
            ->values();

        return [
            'record' => $record,
            'acolhido' => $record->acolhido,
            'avaliacoes' => $avaliacoes,
            'usuarios' => $usuarios,
            'mediaDeTodos' => self::calculateMediaDeTodos($record->acolhido_id),
            'totalAvaliadores' => self::countEvaluators($record->acolhido_id),
            'fotoAcolhido' => self::imageDataUri($record->acolhido?->avatar),
            'formatScore' => fn (float $score): string => self::formatScore($score),
            'scoreColor' => fn (float $score): string => self::scoreColor($score),
        ];
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
            Notification::make()
                ->title('Avaliacao pendente')
                ->body("O acolhido {$avaliacao->acolhido->nome_completo_paciente} ainda precisa da sua avaliacao.")
                ->warning()
                ->icon('heroicon-o-clipboard-document-check')
                ->sendToDatabase($usersPendingEvaluation);
        }

        if ($usersWhoEvaluated->isNotEmpty()) {
            Notification::make()
                ->title('Media de avaliacao atualizada')
                ->body("O acolhido {$avaliacao->acolhido->nome_completo_paciente} ja possui media de todos: " . self::formatScore(self::calculateMediaDeTodos($avaliacao->acolhido_id)) . '.')
                ->success()
                ->icon('heroicon-o-chart-bar')
                ->sendToDatabase($usersWhoEvaluated);
        }
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
