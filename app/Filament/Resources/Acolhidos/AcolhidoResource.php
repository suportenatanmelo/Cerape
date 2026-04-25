<?php

namespace App\Filament\Resources\Acolhidos;

use App\Filament\Resources\Acolhidos\Pages\CreateAcolhido;
use App\Filament\Resources\Acolhidos\Pages\EditAcolhido;
use App\Filament\Resources\Acolhidos\Pages\ListAcolhidos;
use App\Filament\Resources\Acolhidos\Pages\ViewAcolhido;
use App\Filament\Resources\Acolhidos\Schemas\AcolhidoForm;
use App\Filament\Resources\Acolhidos\Schemas\AcolhidoInfolist;
use App\Filament\Resources\Acolhidos\Tables\AcolhidosTable;
use App\Models\Acolhido;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AcolhidoResource extends Resource
{
    protected static ?string $model = Acolhido::class;

    protected static string | UnitEnum | null $navigationGroup = 'Cadastros';

    protected static ?string $navigationLabel = 'Acolhidos';

    protected static string | BackedEnum | null $navigationIcon = Heroicon::Users;

    protected static ?string $recordTitleAttribute = 'Nome_Completo_Paciente';

    public static function form(Schema $schema): Schema
    {
        return AcolhidoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AcolhidoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AcolhidosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'Nome_Completo_Paciente',
            'Nome_da_Mae',
            'Nome_do_Pai',
            'Numero_do_Telefone',
        ];
    }

    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string
    {
        return (string) $record->Nome_Completo_Paciente;
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Telefone' => $record->Numero_do_Telefone ?: '-',
            'Município' => $record->Municipio ?: '-',
            'UF' => $record->UF_Municipio ?: '-',
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAcolhidos::route('/'),
            'create' => CreateAcolhido::route('/create'),
            'view' => ViewAcolhido::route('/{record}'),
            'edit' => EditAcolhido::route('/{record}/edit'),
        ];
    }
}
