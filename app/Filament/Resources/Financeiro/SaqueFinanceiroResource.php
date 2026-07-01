<?php

namespace App\Filament\Resources\Financeiro;

use App\Filament\Resources\Financeiro\Pages\ManageSaquesFinanceiros;
use App\Filament\Resources\Financeiro\Pages\CreateSaqueFinanceiro;
use App\Filament\Resources\Financeiro\Schemas\SaqueFinanceiroForm;
use App\Filament\Resources\Financeiro\Tables\SaqueFinanceiroTable;
use App\Models\SaqueFinanceiro;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class SaqueFinanceiroResource extends Resource
{
    protected static ?string $model = SaqueFinanceiro::class;
    protected static string|UnitEnum|null $navigationGroup = 'Financeiro';
    protected static ?string $navigationLabel = 'Saques';
    protected static ?int $navigationSort = 4;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-down-on-square';
    public static function form(Schema $schema): Schema { return SaqueFinanceiroForm::configure($schema); }
    public static function table(Table $table): Table { return SaqueFinanceiroTable::configure($table); }
    public static function getPages(): array { return ['index' => CreateSaqueFinanceiro::route('/')]; }
}
