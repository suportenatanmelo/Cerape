<?php

namespace App\Filament\Frontend\Resources\ContactPages;

use App\Filament\Frontend\Resources\ContactPages\Pages\ManageContactPages;
use App\Filament\Frontend\Resources\ContactPages\Schemas\ContactPageForm;
use App\Filament\Frontend\Resources\ContactPages\Tables\ContactPagesTable;
use App\Models\ContactPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ContactPageResource extends Resource
{
    protected static ?string $model = ContactPage::class;

    protected static string|UnitEnum|null $navigationGroup = 'Site Publico';

    protected static ?string $navigationLabel = 'Contato';

    protected static ?string $modelLabel = 'pagina de contato';

    protected static ?string $pluralModelLabel = 'paginas de contato';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return ContactPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContactPagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageContactPages::route('/'),
        ];
    }
}
