<?php

namespace App\Filament\Resources\AcolhidoGalerias;

use App\Filament\Resources\AcolhidoGalerias\Pages\ManageAcolhidoGalerias;
use App\Filament\Resources\AcolhidoGalerias\Pages\ViewAcolhidoGaleria;
use App\Filament\Resources\AcolhidoGalerias\Schemas\AcolhidoGaleriaForm;
use App\Filament\Resources\AcolhidoGalerias\Schemas\AcolhidoGaleriaInfolist;
use App\Filament\Resources\AcolhidoGalerias\Tables\AcolhidoGaleriasTable;
use App\Models\AcolhidoGaleria;
use App\Support\AcolhidoAccess;
use App\Support\PortalContext;
use App\Support\PortalResourceAuthorization;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class AcolhidoGaleriaResource extends Resource
{
    protected static ?string $model = AcolhidoGaleria::class;

    protected static string|UnitEnum|null $navigationGroup = 'Documentos e Reunioes';

    protected static ?string $navigationLabel = 'Albuns de imagens';

    protected static ?string $modelLabel = 'album de imagens';

    protected static ?string $pluralModelLabel = 'albuns de imagens';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static ?string $recordTitleAttribute = 'titulo';

    public static function form(Schema $schema): Schema
    {
        return AcolhidoGaleriaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AcolhidoGaleriasTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AcolhidoGaleriaInfolist::configure($schema);
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

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'titulo',
            'descricao',
            'acolhido.nome_completo_paciente',
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAcolhidoGalerias::route('/'),
            'view' => ViewAcolhidoGaleria::route('/{record}'),
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
        return PortalResourceAuthorization::canViewAny(auth()->user(), 'AcolhidoGaleria');
    }

    public static function canCreate(): bool
    {
        return PortalResourceAuthorization::canManage(auth()->user(), 'AcolhidoGaleria', 'create');
    }

    public static function canView(Model $record): bool
    {
        return PortalResourceAuthorization::canViewRecord(auth()->user(), 'AcolhidoGaleria', $record->acolhido_id);
    }

    public static function canEdit(Model $record): bool
    {
        return PortalResourceAuthorization::canManage(auth()->user(), 'AcolhidoGaleria', 'update');
    }

    public static function canDelete(Model $record): bool
    {
        return PortalResourceAuthorization::canManage(auth()->user(), 'AcolhidoGaleria', 'delete');
    }

    public static function canDeleteAny(): bool
    {
        return PortalResourceAuthorization::canManage(auth()->user(), 'AcolhidoGaleria', 'deleteAny');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()->count();

        return (string) $count;
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'info';
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return (string) ($record->titulo ?: $record->acolhido?->nome_completo_paciente ?: 'Album');
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Acolhido' => $record->acolhido?->nome_completo_paciente ?: '-',
            'Descrição' => $record->descricao ?: '-',
        ];
    }
}
