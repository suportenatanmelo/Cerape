<?php

namespace App\Filament\Frontend\Resources;

use App\Filament\Frontend\Resources\TeamMemberResource\Pages\ManageTeamMembers;
use App\Models\TeamMember;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class TeamMemberResource extends Resource
{
    protected static ?string $model = TeamMember::class;
    protected static string|UnitEnum|null $navigationGroup = 'Site público';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Equipe';
    protected static ?string $modelLabel = 'profissional';
    protected static ?string $pluralModelLabel = 'profissionais';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            \Filament\Schemas\Components\Section::make('Profissional')->schema([
                \Filament\Forms\Components\TextInput::make('name')->label('Nome')->required(),
                \Filament\Forms\Components\TextInput::make('role')->label('Função')->required(),
                \Filament\Forms\Components\Textarea::make('description')->label('Descrição breve')->required()->rows(4),
                \Filament\Forms\Components\FileUpload::make('photo_path')->label('Foto')->disk('public')->image()->directory(\App\Support\ImageStorageNaming::directory('equipe_tecnica')),
                \Filament\Forms\Components\TextInput::make('position')->label('Ordem')->numeric()->default(1),
                \Filament\Forms\Components\Toggle::make('hidden')->label('Oculto no site')->default(false)->helperText('Se ativado, o profissional não aparece no frontend, mas permanece cadastrado.'),
                \Filament\Forms\Components\Toggle::make('active')->label('Ativo')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            \Filament\Tables\Columns\ImageColumn::make('photo_path')
                ->label('Foto')
                ->getStateUsing(fn (TeamMember $record): ?string => $record->photoUrl())
                ->size(48)
                ->circular(),
            \Filament\Tables\Columns\TextColumn::make('name')->label('Nome')->searchable(),
            \Filament\Tables\Columns\TextColumn::make('role')->label('Função')->searchable(),
            \Filament\Tables\Columns\TextColumn::make('description')->label('Descrição')->limit(60),
            \Filament\Tables\Columns\IconColumn::make('active')->boolean()->label('Ativo'),
        ])->recordActions([
            ActionGroup::make([
                Action::make('visualizar')
                    ->label('Visualizar')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Visualizar profissional')
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
        return ['index' => ManageTeamMembers::route('/')];
    }
}
