<?php

namespace App\Filament\Resources\AcolhidoVideos;

use App\Filament\Resources\AcolhidoVideos\Pages\ManageAcolhidoVideos;
use App\Filament\Resources\AcolhidoVideos\Schemas\AcolhidoVideoForm;
use App\Filament\Resources\AcolhidoVideos\Tables\AcolhidoVideosTable;
use App\Models\AcolhidoVideo;
use App\Support\AcolhidoAccess;
use App\Support\PortalContext;
use App\Support\PortalResourceAuthorization;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
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
        $user = auth()->user();

        return PortalResourceAuthorization::scopeVisibleRecords(
            AcolhidoAccess::scopeQueryToAcolhido(parent::getEloquentQuery(), $user),
            $user,
        );
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        $user = auth()->user();

        return PortalResourceAuthorization::scopeVisibleRecords(
            AcolhidoAccess::scopeQueryToAcolhido(parent::getGlobalSearchEloquentQuery(), $user),
            $user,
        );
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAcolhidoVideos::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'titulo',
            'descricao',
            'acolhido.nome_completo_paciente',
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return (string) ($record->titulo ?: 'Video do YouTube');
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Acolhido' => $record->acolhido?->nome_completo_paciente ?: '-',
            'Link' => $record->youtube_url ?: '-',
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

    public static function canViewAny(): bool
    {
        return PortalResourceAuthorization::canViewAny(auth()->user(), 'AcolhidoVideo');
    }

    public static function canCreate(): bool
    {
        return PortalResourceAuthorization::canManage(auth()->user(), 'AcolhidoVideo', 'create');
    }

    public static function canView(Model $record): bool
    {
        return PortalResourceAuthorization::canViewRecord(auth()->user(), 'AcolhidoVideo', $record->acolhido_id);
    }

    public static function canEdit(Model $record): bool
    {
        return PortalResourceAuthorization::canManage(auth()->user(), 'AcolhidoVideo', 'update');
    }

    public static function canDelete(Model $record): bool
    {
        return PortalResourceAuthorization::canManage(auth()->user(), 'AcolhidoVideo', 'delete');
    }

    public static function canDeleteAny(): bool
    {
        return PortalResourceAuthorization::canManage(auth()->user(), 'AcolhidoVideo', 'deleteAny');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()->count();

        return (string) $count;
    }
}
