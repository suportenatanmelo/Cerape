<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AcolhidosCadastrosChart;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class Widgets extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'widgets';

    protected static ?string $title = 'Widgets';

    protected static ?string $navigationLabel = 'Widgets';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-pie';

    protected static string|\UnitEnum|null $navigationGroup = 'Widgets';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return false;
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'lg' => 2,
                ])->schema($this->getWidgetsSchemaComponents([
                    AcolhidosCadastrosChart::class,
                ])),
            ]);
    }
}
