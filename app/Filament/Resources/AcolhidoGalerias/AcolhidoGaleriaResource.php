<?php

namespace App\Filament\Resources\AcolhidoGalerias;

use App\Filament\Resources\AcolhidoGalerias\Pages\ManageAcolhidoGalerias;
use App\Filament\Resources\AcolhidoGalerias\Pages\ViewAcolhidoGaleria;
use App\Filament\Resources\AcolhidoGalerias\Schemas\AcolhidoGaleriaForm;
use App\Filament\Resources\AcolhidoGalerias\Schemas\AcolhidoGaleriaInfolist;
use App\Filament\Resources\AcolhidoGalerias\Tables\AcolhidoGaleriasTable;
use App\Models\AcolhidoGaleria;
use App\Support\PortalContext;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class AcolhidoGaleriaResource extends Resource
{
    protected static ?string $model = AcolhidoGaleria::class;

    protected static string|UnitEnum|null $navigationGroup = 'Documentos e Reunioes';

    protected static ?string $navigationLabel = 'Galeria de imagens';

    protected static ?string $modelLabel = 'galeria de imagens';

    protected static ?string $pluralModelLabel = 'galerias de imagens';

    protected static ?int $navigationSort = 3;

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

    public static function getPages(): array
    {
        return [
            'index' => ManageAcolhidoGalerias::route('/'),
            'view' => ViewAcolhidoGaleria::route('/{record}'),
        ];
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return PortalContext::portalNavigationGroup();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return ! PortalContext::isFamilyUser();
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'info';
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return (string) ($record->titulo ?: $record->acolhido?->nome_completo_paciente ?: 'Galeria');
    }
}
