<?php

namespace App\Filament\Frontend\Resources\ThemeProfiles;

use App\Filament\Frontend\Resources\ThemeProfiles\Pages\ManageThemeProfiles;
use App\Filament\Frontend\Resources\ThemeProfiles\Schemas\ThemeProfileForm;
use App\Filament\Frontend\Resources\ThemeProfiles\Tables\ThemeProfilesTable;
use App\Models\FrontendThemeProfile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ThemeProfileResource extends Resource
{
    protected static ?string $model = FrontendThemeProfile::class;

    protected static string|UnitEnum|null $navigationGroup = 'Site Publico';

    protected static ?string $navigationLabel = 'Paleta e fontes';

    protected static ?string $modelLabel = 'perfil visual';

    protected static ?string $pluralModelLabel = 'perfis visuais';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return ThemeProfileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ThemeProfilesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageThemeProfiles::route('/'),
        ];
    }
}
