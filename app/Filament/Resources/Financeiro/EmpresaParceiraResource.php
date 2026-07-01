<?php

namespace App\Filament\Resources\Financeiro;

use App\Filament\Resources\Financeiro\Pages\ManageEmpresaParceiras;
use App\Filament\Resources\Financeiro\Schemas\EmpresaParceiraForm;
use App\Filament\Resources\Financeiro\Tables\EmpresaParceiraTable;
use App\Models\EmpresaParceira;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class EmpresaParceiraResource extends Resource
{
    protected static ?string $model = EmpresaParceira::class;
    protected static string|UnitEnum|null $navigationGroup = 'Financeiro';
    protected static ?string $navigationLabel = 'Empresas parceiras';
    protected static ?int $navigationSort = 1;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Schema $schema): Schema
    {
        return EmpresaParceiraForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmpresaParceiraTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEmpresaParceiras::route('/'),
        ];
    }
}
