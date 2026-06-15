<?php

namespace App\Filament\Frontend\Resources\CarouselSlides;

use App\Filament\Frontend\Resources\CarouselSlides\Pages\ManageCarouselSlides;
use App\Filament\Frontend\Resources\CarouselSlides\Schemas\CarouselSlideForm;
use App\Filament\Frontend\Resources\CarouselSlides\Tables\CarouselSlidesTable;
use App\Models\CarouselSlide;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CarouselSlideResource extends Resource
{
    protected static ?string $model = CarouselSlide::class;

    protected static string|UnitEnum|null $navigationGroup = 'Site Publico';

    protected static ?string $navigationLabel = 'Carrossel';

    protected static ?string $modelLabel = 'slide';

    protected static ?string $pluralModelLabel = 'slides';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return CarouselSlideForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarouselSlidesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCarouselSlides::route('/'),
        ];
    }
}
