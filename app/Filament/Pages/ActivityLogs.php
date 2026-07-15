<?php

namespace App\Filament\Pages;

use App\Models\ActivityLog;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use UnitEnum;

class ActivityLogs extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Logs de atividade';

    protected static string|UnitEnum|null $navigationGroup = 'Administração e Acesso';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $title = 'Logs de atividade';

    protected string $view = 'filament.pages.activity-logs';

    public static function shouldRegisterNavigation(): bool
    {
        // Disable page navigation to avoid conflicting route with ActivityLogResource
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ActivityLog::query()->latest('executed_at')->latest('id')
            )
            ->columns([
                TextColumn::make('executed_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Usuário')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('module')
                    ->label('Módulo')
                    ->searchable(),
                TextColumn::make('action')
                    ->label('Ação')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Descrição')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('ip')
                    ->label('IP')
                    ->searchable(),
            ])
            ->paginated([10, 25, 50])
            ->defaultSort('executed_at', 'desc');
    }
}
