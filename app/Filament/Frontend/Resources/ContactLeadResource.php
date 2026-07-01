<?php

namespace App\Filament\Frontend\Resources;

use App\Filament\Frontend\Resources\ContactLeadResource\Pages\ManageContactLeads;
use App\Models\ContactLead;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class ContactLeadResource extends Resource
{
    protected static ?string $model = ContactLead::class;
    protected static string|UnitEnum|null $navigationGroup = 'Site público';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Contatos';
    protected static ?string $modelLabel = 'contato';
    protected static ?string $pluralModelLabel = 'contatos';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('nome')->label('Nome')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('telefone')->label('Telefone'),
                \Filament\Tables\Columns\TextColumn::make('email')->label('E-mail')->toggleable(isToggledHiddenByDefault: true),
                \Filament\Tables\Columns\TextColumn::make('mensagem')->label('Mensagem')->limit(60),
                \Filament\Tables\Columns\IconColumn::make('respondido')->boolean()->label('Respondido'),
                \Filament\Tables\Columns\TextColumn::make('created_at')->label('Data')->dateTime(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('visualizar')
                        ->label('Visualizar')
                        ->icon('heroicon-o-eye')
                        ->modalHeading('Visualizar contato')
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Fechar')
                        ->modalContent(fn ($record) => view('filament.frontend.record-preview', ['record' => $record])),
                    Action::make('whatsapp')
                        ->label('Responder')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->url(fn ($record) => "https://wa.me/55{$record->telefone}?text=Olá {$record->nome}, recebemos seu contato.")
                        ->openUrlInNewTab(),
                    EditAction::make()->label('Editar'),
                    DeleteAction::make()->label('Deletar'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageContactLeads::route('/')];
    }
}
