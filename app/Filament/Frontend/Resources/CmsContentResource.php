<?php

namespace App\Filament\Frontend\Resources;

use App\Filament\Frontend\Resources\CmsContentResource\Pages\ManageCmsContents;
use App\Models\CmsContent;
use App\Support\CmsContentHelper;
use App\Support\ImageStorageNaming;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class CmsContentResource extends Resource
{
    protected static ?string $model = CmsContent::class;
    protected static string|UnitEnum|null $navigationGroup = 'Conteúdo';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'Conteúdos CMS';
    protected static ?string $modelLabel = 'conteúdo';
    protected static ?string $pluralModelLabel = 'conteúdos';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Conteúdo institucional')
                ->description(fn (?Model $record): string => CmsContentHelper::helperText($record instanceof CmsContent ? $record->type : null))
                ->schema([
                    Select::make('type')
                        ->label('Módulo')
                        ->options(CmsContent::TYPES)
                        ->required()
                        ->searchable()
                        ->helperText('Escolha onde este conteúdo será usado no site público.'),
                    TextInput::make('title')
                        ->label('Título')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Ex.: Tratamento individualizado')
                        ->helperText('Nome principal exibido no site.'),
                    TextInput::make('slug')
                        ->label('Slug')
                        ->maxLength(255)
                        ->placeholder('tratamento-individualizado')
                        ->helperText('Opcional. Use para URLs e busca interna.'),
                    TextInput::make('subtitle')
                        ->label('Subtítulo')
                        ->maxLength(255)
                        ->placeholder('Chamada complementar do conteúdo.'),
                    Textarea::make('summary')
                        ->label('Resumo')
                        ->rows(3)
                        ->placeholder('Resumo curto para cards e listagens.')
                        ->columnSpanFull(),
                    RichEditor::make('content')
                        ->label('Texto completo')
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Mídia e ação')
                ->schema([
                    FileUpload::make('image_path')
                        ->label('Imagem desktop / principal')
                        ->disk('public')
                        ->image()
                        ->directory(ImageStorageNaming::directory('cms'))
                        ->helperText('Imagem principal usada em cards, banners ou páginas.'),
                    FileUpload::make('mobile_image_path')
                        ->label('Imagem mobile')
                        ->disk('public')
                        ->image()
                        ->directory(ImageStorageNaming::directory('cms'))
                        ->helperText('Opcional para banners e popups responsivos.'),
                    TextInput::make('icon')
                        ->label('Ícone')
                        ->placeholder('heroicon-o-heart ou nome de classe')
                        ->maxLength(255),
                    TextInput::make('cta_label')
                        ->label('Texto do botão')
                        ->placeholder('Saiba mais')
                        ->maxLength(255),
                    TextInput::make('cta_url')
                        ->label('URL do botão')
                        ->placeholder('/contato ou https://...')
                        ->maxLength(255),
                    TextInput::make('external_url')
                        ->label('URL externa')
                        ->placeholder('https://site-do-parceiro.com')
                        ->maxLength(255),
                ])->columns(2),

            Section::make('Organização e agendamento')
                ->schema([
                    TextInput::make('category')
                        ->label('Categoria')
                        ->placeholder('Ex.: Família, Tratamento, Institucional'),
                    TagsInput::make('tags')
                        ->label('Tags')
                        ->placeholder('Digite uma tag e pressione Enter'),
                    TextInput::make('position')
                        ->label('Ordem')
                        ->numeric()
                        ->default(1)
                        ->required(),
                    DateTimePicker::make('starts_at')
                        ->label('Data inicial')
                        ->helperText('Opcional. Conteúdo só aparece após esta data.'),
                    DateTimePicker::make('ends_at')
                        ->label('Data final')
                        ->helperText('Opcional. Conteúdo sai automaticamente após esta data.'),
                    Toggle::make('is_featured')
                        ->label('Destaque')
                        ->default(false),
                    Toggle::make('is_active')
                        ->label('Ativo')
                        ->default(true),
                ])->columns(3),

            Section::make('SEO')
                ->schema([
                    TextInput::make('meta_title')
                        ->label('Meta title')
                        ->maxLength(255)
                        ->placeholder('Título para mecanismos de busca'),
                    Textarea::make('meta_description')
                        ->label('Meta description')
                        ->rows(2)
                        ->placeholder('Descrição para mecanismos de busca'),
                    TextInput::make('canonical_url')
                        ->label('Canonical')
                        ->placeholder('https://...'),
                    FileUpload::make('og_image_path')
                        ->label('Open Graph / Twitter Card')
                        ->disk('public')
                        ->image()
                        ->directory(ImageStorageNaming::directory('cms')),
                ])->columns(2)
                    ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Imagem')
                    ->getStateUsing(fn (CmsContent $record): ?string => $record->imageUrl())
                    ->size(48),
                TextColumn::make('type')
                    ->label('Módulo')
                    ->formatStateUsing(fn (string $state): string => CmsContent::TYPES[$state] ?? $state)
                    ->badge()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Categoria')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('position')
                    ->label('Ordem')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Módulo')
                    ->options(CmsContent::TYPES),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('duplicar')
                        ->label('Duplicar')
                        ->icon('heroicon-o-document-duplicate')
                        ->action(function (CmsContent $record): void {
                            $copy = $record->replicate();
                            $copy->title = $record->title . ' (cópia)';
                            $copy->slug = filled($record->slug) ? $record->slug . '-copia-' . now()->format('His') : null;
                            $copy->is_active = false;
                            $copy->save();
                        }),
                    Action::make('visualizar')
                        ->label('Preview')
                        ->icon('heroicon-o-eye')
                        ->modalHeading('Preview do conteúdo')
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Fechar')
                        ->modalContent(fn (CmsContent $record) => view('filament.frontend.record-preview', ['record' => $record])),
                    EditAction::make()->label('Editar'),
                    DeleteAction::make()->label('Excluir'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageCmsContents::route('/')];
    }
}
