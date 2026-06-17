<?php

namespace App\Filament\Frontend\Resources\FooterSettings;

use App\Filament\Frontend\Resources\FooterSettings\Pages\ManageFooterSettings;
use App\Filament\Frontend\Resources\FooterSettings\Schemas\FooterSettingForm;
use App\Filament\Frontend\Resources\FooterSettings\Tables\FooterSettingsTable;
use App\Models\FrontendFooterSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class FooterSettingResource extends Resource
{
    protected static ?string $model = FrontendFooterSetting::class;

    protected static string|UnitEnum|null $navigationGroup = 'Site Publico';

    protected static ?string $navigationLabel = 'Rodapé';

    protected static ?string $modelLabel = 'rodapé';

    protected static ?string $pluralModelLabel = 'rodapés';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static ?string $recordTitleAttribute = 'brand_name';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return FooterSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FooterSettingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageFooterSettings::route('/'),
        ];
    }
}
