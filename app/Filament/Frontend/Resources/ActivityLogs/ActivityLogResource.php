<?php

namespace App\Filament\Frontend\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs as BaseList;
use App\Filament\Resources\ActivityLogs\Pages\ViewActivityLog as BaseView;
use App\Models\ActivityLog;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static string|\UnitEnum|null $navigationGroup = 'Controle de acesso e auditoria';

    protected static ?string $navigationLabel = 'Auditoria';

    protected static ?string $modelLabel = 'Registro de auditoria';

    protected static ?string $pluralModelLabel = 'Auditoria';

    protected static ?int $navigationSort = 101;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return \App\Filament\Resources\ActivityLogs\ActivityLogResource::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => BaseList::route('/'),
            'view' => BaseView::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with('user');
    }
}
