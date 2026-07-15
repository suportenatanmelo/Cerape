<?php

namespace App\Filament\Resources\CmsSeo;

use App\Cms\Models\Seo;
use App\Filament\Resources\CmsSeo\Pages\ManageSeo;
use App\Support\PortalContext;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class SeoResource extends Resource
{
    protected static ?string $model = Seo::class;

    protected static ?string $navigationLabel = 'SEO';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';

    protected static string|UnitEnum|null $navigationGroup = 'Documentos e Relatorios';

    protected static ?int $navigationSort = 25;

    protected static ?string $recordTitleAttribute = 'meta_title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Metadados')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta title')
                            ->maxLength(255),
                        Textarea::make('meta_description')
                            ->label('Meta description')
                            ->rows(3),
                        Textarea::make('meta_keywords')
                            ->label('Palavras-chave')
                            ->rows(2),
                        TextInput::make('canonical')
                            ->label('Canonical')
                            ->maxLength(1024),
                        Textarea::make('open_graph')
                            ->label('Open Graph')
                            ->rows(4)
                            ->helperText('JSON ou dados de Open Graph.'),
                        TextInput::make('model_type')
                            ->label('Model type')
                            ->maxLength(255),
                        TextInput::make('model_id')
                            ->label('Model id')
                            ->numeric(),
                        Toggle::make('active')
                            ->label('Ativo')
                            ->default(true),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('meta_title')
                    ->label('Meta title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('model_type')
                    ->label('Modelo')
                    ->placeholder('-'),
                TextColumn::make('model_id')
                    ->label('ID do modelo')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSeo::route('/'),
        ];
    }

    public static function getNavigationGroup(): string | UnitEnum | null
    {
        return PortalContext::documentsNavigationGroup();
    }
}
