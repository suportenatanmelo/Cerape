<?php

namespace App\Filament\Resources\AcolhidoVideos;

use App\Filament\Resources\AcolhidoVideos\Pages\ManageAcolhidoVideos;
use App\Filament\Resources\AcolhidoVideos\Schemas\AcolhidoVideoForm;
use App\Filament\Resources\AcolhidoVideos\Tables\AcolhidoVideosTable;
use App\Models\AcolhidoVideo;
use App\Support\AcolhidoAccess;
use App\Support\PortalContext;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class AcolhidoVideoResource extends Resource
{
    protected static ?string $model = AcolhidoVideo::class;

    protected static string|UnitEnum|null $navigationGroup = 'Uploads de videos';

    protected static ?string $navigationLabel = 'Videos do YouTube';

    protected static ?string $modelLabel = 'video do YouTube';

    protected static ?string $pluralModelLabel = 'videos do YouTube';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-play-circle';

    protected static ?string $recordTitleAttribute = 'titulo';

    public static function form(Schema $schema): Schema
    {
        return AcolhidoVideoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AcolhidoVideosTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return AcolhidoAccess::scopeQueryToAcolhido(parent::getEloquentQuery(), auth()->user());
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return AcolhidoAccess::scopeQueryToAcolhido(parent::getGlobalSearchEloquentQuery(), auth()->user());
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAcolhidoVideos::route('/'),
        ];
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return PortalContext::mediaNavigationGroup();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && static::canViewAny();
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()->count();

        return $count > 0 ? (string) $count : null;
    }
}
