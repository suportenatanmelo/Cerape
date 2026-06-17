<?php

namespace App\Filament\Frontend\Resources\Testimonials;

use App\Filament\Frontend\Resources\Testimonials\Pages\ManageTestimonials;
use App\Filament\Frontend\Resources\Testimonials\Schemas\TestimonialForm;
use App\Filament\Frontend\Resources\Testimonials\Tables\TestimonialsTable;
use App\Models\FrontendTestimonial;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TestimonialResource extends Resource
{
    protected static ?string $model = FrontendTestimonial::class;

    protected static string|UnitEnum|null $navigationGroup = 'Site Publico';

    protected static ?string $navigationLabel = 'Depoimentos';

    protected static ?string $modelLabel = 'depoimento';

    protected static ?string $pluralModelLabel = 'depoimentos';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return TestimonialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TestimonialsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTestimonials::route('/'),
        ];
    }
}
