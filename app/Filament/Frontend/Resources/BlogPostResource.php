<?php

namespace App\Filament\Frontend\Resources;

use App\Filament\Frontend\Resources\BlogPostResource\Pages\ManageBlogPosts;
use App\Models\BlogPost;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;
    protected static string|UnitEnum|null $navigationGroup = 'Conteúdo';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Blog';
    protected static ?string $modelLabel = 'artigo';
    protected static ?string $pluralModelLabel = 'artigos';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            \Filament\Schemas\Components\Section::make('Artigo do blog')->schema([
                \Filament\Forms\Components\TextInput::make('title')->label('Título')->required(),
                \Filament\Forms\Components\TextInput::make('slug')->label('Slug')->required(),
                \Filament\Forms\Components\Textarea::make('excerpt')->label('Resumo')->required()->rows(3),
                \Filament\Forms\Components\RichEditor::make('content')->label('Conteúdo')->required()->columnSpanFull(),
                \Filament\Forms\Components\TextInput::make('author_name')->label('Autor')->required(),
                \Filament\Forms\Components\DateTimePicker::make('published_at')->label('Data de publicação'),
                \Filament\Forms\Components\FileUpload::make('image_path')
                    ->label('Imagem')
                    ->disk('public')
                    ->image()
                    ->directory(\App\Support\ImageStorageNaming::directory('blog'))
                    ->preserveFilenames()
                    ->acceptedFileTypes(['image/jpeg','image/png','image/webp','image/gif']),
                \Filament\Forms\Components\TagsInput::make('tags')->label('Tags'),
                \Filament\Forms\Components\TextInput::make('position')->label('Ordem')->numeric()->default(1),
                \Filament\Forms\Components\Toggle::make('show_on_home')->label('Mostrar no site principal')->default(true),
                \Filament\Forms\Components\Toggle::make('show_in_blog')->label('Mostrar na página do blog')->default(true),
                \Filament\Forms\Components\Toggle::make('active')->label('Ativo')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            \Filament\Tables\Columns\ImageColumn::make('image_path')
                ->label('Imagem')
                ->getStateUsing(fn (BlogPost $record): ?string => $record->imageUrl())
                ->size(64),
            \Filament\Tables\Columns\TextColumn::make('title')->label('Título')->searchable(),
            \Filament\Tables\Columns\TextColumn::make('author_name')->label('Autor'),
            \Filament\Tables\Columns\TextColumn::make('published_at')->label('Data')->dateTime(),
            \Filament\Tables\Columns\TextColumn::make('tags')->label('Tags')->badge(),
            \Filament\Tables\Columns\IconColumn::make('active')->boolean()->label('Ativo'),
        ])->recordActions([
            ActionGroup::make([
                Action::make('visualizar')
                    ->label('Visualizar')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Visualizar artigo')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Fechar')
                    ->modalContent(fn ($record) => view('filament.frontend.record-preview', ['record' => $record])),
                EditAction::make()->label('Editar'),
                DeleteAction::make()->label('Deletar'),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageBlogPosts::route('/')];
    }
}
