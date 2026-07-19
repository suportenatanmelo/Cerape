<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs;
use App\Filament\Resources\ActivityLogs\Pages\ViewActivityLog;
use App\Models\ActivityLog;
use App\Models\User;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|UnitEnum|null $navigationGroup = 'Administração e acesso';

    protected static ?string $navigationLabel = 'Auditoria';

    protected static ?int $navigationSort = 98;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $recordTitleAttribute = 'description';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->hasAnyRole([User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('executed_at', 'desc')
            ->columns([
                TextColumn::make('executed_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Usuário')
                    ->badge()
                    ->color('primary')
                    ->placeholder('Sistema')
                    ->searchable(),
                TextColumn::make('module')
                    ->label('Módulo')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                TextColumn::make('action')
                    ->label('Ação')
                    ->badge()
                    ->color('warning')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Descrição')
                    ->limit(80)
                    ->wrap()
                    ->searchable(),
                TextColumn::make('model_type')
                    ->label('Modelo')
                    ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '-')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('model_id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('ip')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('module')
                    ->label('Módulo')
                    ->options(fn (): array => ActivityLog::query()
                        ->whereNotNull('module')
                        ->distinct()
                        ->orderBy('module')
                        ->pluck('module', 'module')
                        ->all()),
                SelectFilter::make('action')
                    ->label('Ação')
                    ->options(fn (): array => ActivityLog::query()
                        ->whereNotNull('action')
                        ->distinct()
                        ->orderBy('action')
                        ->pluck('action', 'action')
                        ->all()),
                SelectFilter::make('user_id')
                    ->label('Usuário')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Filter::make('periodo')
                    ->label('Período')
                    ->form([
                        DatePicker::make('de')->label('De'),
                        DatePicker::make('ate')->label('Até'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                filled($data['de'] ?? null),
                                fn (Builder $query): Builder => $query->whereDate('executed_at', '>=', $data['de']),
                            )
                            ->when(
                                filled($data['ate'] ?? null),
                                fn (Builder $query): Builder => $query->whereDate('executed_at', '<=', $data['ate']),
                            );
                    }),
                Filter::make('termo')
                    ->label('Busca livre')
                    ->form([
                        TextInput::make('search')
                            ->label('Buscar em descrição, módulo ou ação'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $term = trim((string) ($data['search'] ?? ''));

                        if ($term === '') {
                            return $query;
                        }

                        return $query->where(function (Builder $query) use ($term): void {
                            $query
                                ->where('module', 'like', '%' . $term . '%')
                                ->orWhere('action', 'like', '%' . $term . '%')
                                ->orWhere('description', 'like', '%' . $term . '%')
                                ->orWhere('model_type', 'like', '%' . $term . '%')
                                ->orWhere('ip', 'like', '%' . $term . '%');
                        });
                    }),
            ])
            ->recordActions([
                ViewAction::make()->label('Detalhar'),
            ])
            ->paginated([15, 25, 50])
            ->striped();
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make([
                'default' => 1,
                'lg' => 2,
            ])->schema([
                Section::make('Resumo')
                    ->description('Dados principais da ação auditada.')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        TextEntry::make('executed_at')
                            ->label('Executado em')
                            ->dateTime('d/m/Y H:i:s')
                            ->badge()
                            ->color('gray'),
                        TextEntry::make('user.name')
                            ->label('Usuário')
                            ->badge()
                            ->color('primary')
                            ->placeholder('Sistema'),
                        TextEntry::make('module')
                            ->label('Módulo')
                            ->badge()
                            ->color('info'),
                        TextEntry::make('action')
                            ->label('Ação')
                            ->badge()
                            ->color('warning'),
                        TextEntry::make('description')
                            ->label('Descrição')
                            ->columnSpanFull()
                            ->placeholder('-'),
                        TextEntry::make('model_type')
                            ->label('Modelo afetado')
                            ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '-')
                            ->placeholder('-'),
                        TextEntry::make('model_id')
                            ->label('ID do modelo')
                            ->placeholder('-'),
                    ])
                    ->columns(2),
                Section::make('Contexto técnico')
                    ->description('Metadados coletados na requisição.')
                    ->icon('heroicon-o-computer-desktop')
                    ->schema([
                        TextEntry::make('ip')->label('IP')->placeholder('-'),
                        TextEntry::make('browser')->label('Browser')->placeholder('-'),
                        TextEntry::make('platform')->label('Plataforma')->placeholder('-'),
                        TextEntry::make('device')->label('Dispositivo')->placeholder('-'),
                        TextEntry::make('method')->label('Método HTTP')->placeholder('-'),
                        TextEntry::make('url')->label('URL')->placeholder('-')->columnSpanFull(),
                        TextEntry::make('session_id')->label('Session ID')->placeholder('-')->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Alterações')
                    ->description('Comparativo entre valores antigos e novos.')
                    ->icon('heroicon-o-arrows-right-left')
                    ->schema([
                        ViewEntry::make('old_values')
                            ->label('Valores antigos')
                            ->view('filament.resources.activity-logs.json')
                            ->columnSpanFull(),
                        ViewEntry::make('new_values')
                            ->label('Valores novos')
                            ->view('filament.resources.activity-logs.json')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
            'view' => ViewActivityLog::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) ActivityLog::query()->whereDate('executed_at', today())->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }

    public static function canView(Model $record): bool
    {
        return static::canAccess();
    }
}
