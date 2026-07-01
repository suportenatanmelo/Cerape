<?php

namespace App\Filament\Frontend\Resources;

use App\Filament\Frontend\Resources\NewsletterSubscriberResource\Pages\ManageNewsletterSubscribers;
use App\Models\NewsletterSubscriber;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class NewsletterSubscriberResource extends Resource
{
    protected static ?string $model = NewsletterSubscriber::class;
    protected static string|UnitEnum|null $navigationGroup = 'Conteúdo';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'Newsletter';
    protected static ?string $modelLabel = 'inscrito';
    protected static ?string $pluralModelLabel = 'inscritos';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Inscrito na newsletter')
                ->description('Gerencie os cadastros recebidos pelo site público e exporte a lista quando necessário.')
                ->schema([
                    TextInput::make('name')
                        ->label('Nome')
                        ->maxLength(255)
                        ->placeholder('Nome do inscrito'),
                    TextInput::make('email')
                        ->label('E-mail')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->placeholder('nome@email.com'),
                    TextInput::make('phone')
                        ->label('Telefone')
                        ->maxLength(30)
                        ->placeholder('(00) 00000-0000'),
                    TextInput::make('source')
                        ->label('Origem')
                        ->placeholder('site, evento, formulário...'),
                    DateTimePicker::make('subscribed_at')
                        ->label('Inscrito em')
                        ->default(now()),
                    DateTimePicker::make('unsubscribed_at')
                        ->label('Descadastrado em'),
                    Toggle::make('is_active')
                        ->label('Ativo')
                        ->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('Nome')->searchable()->placeholder('-'),
            TextColumn::make('email')->label('E-mail')->searchable(),
            TextColumn::make('source')->label('Origem')->placeholder('-'),
            TextColumn::make('subscribed_at')->label('Inscrito em')->dateTime('d/m/Y H:i'),
            IconColumn::make('is_active')->label('Ativo')->boolean(),
        ])->recordActions([
            EditAction::make()->label('Editar'),
            DeleteAction::make()->label('Excluir'),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageNewsletterSubscribers::route('/')];
    }
}
