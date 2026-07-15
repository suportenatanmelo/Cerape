<?php

namespace App\Filament\Resources\CmsBlocks;

use App\Cms\Models\Block;
use App\Filament\Resources\CmsBlocks\Pages\ManageBlocks;
use App\Support\PortalContext;
use BackedEnum;
use Filament\Forms\Components\Grid;
use UnitEnum;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid as SchemaGrid;
use Filament\Schemas\Components\Section as SchemaSection;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BlockResource extends Resource
{
    protected static ?string $model = Block::class;

    protected static ?string $navigationLabel = 'Blocos';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-square-2-stack';

    protected static string|UnitEnum|null $navigationGroup = 'Documentos e Relatorios';

    protected static ?int $navigationSort = 15;

    protected static ?string $recordTitleAttribute = 'type';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaSection::make('Configuração do bloco')
                    ->schema([
                        SchemaGrid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            Select::make('page_id')
                                ->label('Página')
                                ->relationship('page', 'title')
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('type')
                                ->label('Tipo de bloco')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('position')
                                ->label('Posição')
                                ->numeric()
                                ->default(0),
                            Toggle::make('active')
                                ->label('Ativo')
                                ->default(true),
                        ]),
                        Textarea::make('config')
                            ->label('Configuração JSON')
                            ->rows(4)
                            ->helperText('Use JSON para configurações específicas do bloco.'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('position')
            ->columns([
                TextColumn::make('page.title')
                    ->label('Página')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->searchable(),
                BadgeColumn::make('active')
                    ->label('Ativo')
                    ->boolean(),
                TextColumn::make('position')
                    ->label('Posição')
                    ->sortable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageBlocks::route('/'),
        ];
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return PortalContext::documentsNavigationGroup();
    }
}
