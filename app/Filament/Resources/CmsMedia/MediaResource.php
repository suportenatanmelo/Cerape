<?php

namespace App\Filament\Resources\CmsMedia;

use App\Cms\Models\Media;
use App\Filament\Resources\CmsMedia\Pages\ManageMedia;
use App\Support\PortalContext;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static ?string $navigationLabel = 'Mídia';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static string|UnitEnum|null $navigationGroup = 'Midia e Galeria';

    protected static ?int $navigationSort = 30;

    protected static ?string $recordTitleAttribute = 'filename';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalhes da mídia')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            TextInput::make('disk')
                                ->label('Disco')
                                ->default('public')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('path')
                                ->label('Caminho')
                                ->required()
                                ->maxLength(1024),
                            TextInput::make('filename')
                                ->label('Nome do arquivo')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('mime')
                                ->label('MIME')
                                ->maxLength(255),
                            TextInput::make('collection')
                                ->label('Coleção')
                                ->maxLength(255),
                            TextInput::make('alt')
                                ->label('Texto alternativo')
                                ->maxLength(255),
                        ]),
                        TextInput::make('caption')
                            ->label('Legenda')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('copyright')
                            ->label('Copyright')
                            ->maxLength(255)
                            ->columnSpanFull(),
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
                TextColumn::make('filename')
                    ->label('Arquivo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('collection')
                    ->label('Coleção')
                    ->placeholder('-'),
                TextColumn::make('disk')
                    ->label('Disco'),
                TextColumn::make('mime')
                    ->label('MIME')
                    ->placeholder('-'),
                TextColumn::make('size')
                    ->label('Tamanho')
                    ->sortable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMedia::route('/'),
        ];
    }

    public static function getNavigationGroup(): string | UnitEnum | null
    {
        return PortalContext::mediaNavigationGroup();
    }
}
