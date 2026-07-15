<?php

namespace App\Filament\Resources\CmsPages;

use App\Cms\Models\Page;
use App\Filament\Resources\CmsPages\Pages\ManagePages;
use App\Filament\Resources\CmsPages\RelationManagers\BlocksRelationManager;
use App\Support\PortalContext;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationLabel = 'Páginas';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|UnitEnum|null $navigationGroup = 'Documentos e Relatorios';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dados da página')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            TextInput::make('title')
                                ->label('Título')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('slug')
                                ->label('Slug')
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'draft' => 'Rascunho',
                                    'published' => 'Publicado',
                                    'archived' => 'Arquivado',
                                ])
                                ->default('draft')
                                ->required(),
                            Toggle::make('is_homepage')
                                ->label('Página inicial')
                                ->helperText('Marque como homepage quando esta página deve ser exibida como principal.'),
                        ]),
                        Textarea::make('summary')
                            ->label('Resumo')
                            ->rows(3)
                            ->columnSpanFull(),
                        Select::make('parent_id')
                            ->label('Página pai')
                            ->relationship('parent', 'title')
                            ->searchable()
                            ->preload()
                            ->placeholder('Nenhuma'),
                        DateTimePicker::make('published_at')
                            ->label('Publicado em')
                            ->required(false),
                        Textarea::make('settings')
                            ->label('Configurações JSON')
                            ->rows(4)
                            ->helperText('Use JSON para configurações avançadas da página, se necessário.'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                        'secondary' => 'archived',
                    ]),
                TextColumn::make('parent.title')
                    ->label('Página pai')
                    ->placeholder('-'),
                TextColumn::make('published_at')
                    ->label('Publicado em')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Rascunho',
                        'published' => 'Publicado',
                        'archived' => 'Arquivado',
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BlocksRelationManager::class,
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePages::route('/'),
        ];
    }

    public static function getNavigationGroup(): string | UnitEnum | null
    {
        return PortalContext::documentsNavigationGroup();
    }
}
