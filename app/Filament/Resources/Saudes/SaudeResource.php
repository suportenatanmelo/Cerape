<?php

namespace App\Filament\Resources\Saudes;

use App\Filament\Resources\Saudes\Pages\ManageSaudes;
use App\Filament\Resources\Saudes\Pages\ViewSaude;
use App\Filament\Resources\Saudes\Schemas\SaudeForm;
use App\Filament\Resources\Saudes\Schemas\SaudeInfolist;
use App\Filament\Resources\Saudes\Tables\SaudesTable;
use App\Models\Saude;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class SaudeResource extends Resource
{
    protected static ?string $model = Saude::class;

    protected static string | UnitEnum | null $navigationGroup = 'Cadastros';

    protected static ?string $navigationLabel = 'Saúde';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-heart';

    protected static ?string $modelLabel = 'ficha de saude';

    protected static ?string $pluralModelLabel = 'fichas de saude';

    protected static ?string $recordTitleAttribute = 'acolhido_id';

    public static function form(Schema $schema): Schema
    {
        return SaudeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SaudeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SaudesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSaudes::route('/'),
            'view' => ViewSaude::route('/{record}'),
        ];
    }
}
