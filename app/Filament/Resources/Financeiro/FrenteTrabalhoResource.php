<?php

namespace App\Filament\Resources\Financeiro;

use App\Filament\Resources\Financeiro\Pages\ManageFrentesTrabalho;
use App\Filament\Resources\Financeiro\Schemas\FrenteTrabalhoForm;
use App\Filament\Resources\Financeiro\Tables\FrenteTrabalhoTable;
use App\Models\FrenteTrabalho;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class FrenteTrabalhoResource extends Resource
{
    protected static ?string $model = FrenteTrabalho::class;
    protected static string|UnitEnum|null $navigationGroup = 'Financeiro';
    protected static ?string $navigationLabel = 'Frentes de trabalho';
    protected static ?int $navigationSort = 2;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Schema $schema): Schema
    {
        return FrenteTrabalhoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FrenteTrabalhoTable::configure($table);
    }

    public static function getPages(): array
    {
        return ['index' => ManageFrentesTrabalho::route('/')];
    }
}
