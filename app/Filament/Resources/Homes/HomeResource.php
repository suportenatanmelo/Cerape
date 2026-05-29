<?php

namespace App\Filament\Resources\Homes;

use App\Filament\Resources\Homes\Pages\ManageHomes;
use App\Filament\Resources\Homes\Schemas\HomeForm;
use App\Filament\Resources\Homes\Tables\HomesTable;
use App\Models\Home;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HomeResource extends Resource
{
    protected static ?string $model = Home::class;

    protected static ?string $navigationLabel = 'Conteudo da Home';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $modelLabel = 'conteudo da home';

    protected static ?string $pluralModelLabel = 'conteudos da home';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return HomeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HomesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageHomes::route('/'),
        ];
    }

    public static function getNavigationGroup(): string | UnitEnum | null
    {
        return \App\Support\PortalContext::mediaNavigationGroup();
    }
}
