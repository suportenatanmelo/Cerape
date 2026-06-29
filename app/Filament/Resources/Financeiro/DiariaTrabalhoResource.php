<?php

namespace App\Filament\Resources\Financeiro;

use App\Filament\Resources\Financeiro\Pages\ManageDiariasTrabalho;
use App\Filament\Resources\Financeiro\Schemas\DiariaTrabalhoForm;
use App\Filament\Resources\Financeiro\Tables\DiariaTrabalhoTable;
use App\Models\DiariaTrabalho;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class DiariaTrabalhoResource extends Resource
{
    protected static ?string $model = DiariaTrabalho::class;
    protected static string|UnitEnum|null $navigationGroup = 'Financeiro';
    protected static ?string $navigationLabel = 'Diárias de trabalho';
    protected static ?int $navigationSort = 3;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Schema $schema): Schema
    {
        return DiariaTrabalhoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DiariaTrabalhoTable::configure($table);
    }

    public static function getPages(): array
    {
        return ['index' => ManageDiariasTrabalho::route('/')];
    }
}
