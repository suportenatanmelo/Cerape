<?php

namespace App\Filament\Resources\DemandasAcolhidos;

use App\Filament\Resources\Acolhidos\AcolhidoResource;
use App\Filament\Resources\DemandasAcolhidos\Pages\CreateDemandaAcolhido;
use App\Filament\Resources\DemandasAcolhidos\Pages\EditDemandaAcolhido;
use App\Filament\Resources\DemandasAcolhidos\Pages\ListDemandasAcolhidos;
use App\Filament\Resources\DemandasAcolhidos\Pages\ViewDemandaAcolhido;
use App\Models\DemandaAcolhido;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class DemandaAcolhidoResource extends Resource
{
    protected static ?string $model = DemandaAcolhido::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|UnitEnum|null $navigationGroup = 'CADASTROS';

    protected static ?string $navigationLabel = 'Demandas assistenciais';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'demanda';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('acolhido_id')
                ->label('Acolhido')
                ->relationship('acolhido', 'nome_completo_paciente')
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('demanda')
                ->label('Demanda')
                ->required()
                ->maxLength(255),
            DateTimePicker::make('saida_prevista_em')
                ->label('Saída prevista')
                ->required(),
            DateTimePicker::make('retorno_previsto_em')
                ->label('Retorno previsto')
                ->required(),
            FileUpload::make('arquivo_path')
                ->label('Arquivo')
                ->disk('public')
                ->directory('demandas_acolhidos')
                ->preserveFilenames(),
            Textarea::make('observacoes')
                ->label('Observações')
                ->rows(4)
                ->columnSpanFull(),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('acolhido.nome_completo_paciente')->label('Acolhido'),
            TextEntry::make('demanda')->label('Demanda'),
            TextEntry::make('saida_prevista_em')->dateTime()->label('Saída prevista'),
            TextEntry::make('retorno_previsto_em')->dateTime()->label('Retorno previsto'),
            TextEntry::make('observacoes')->label('Observações')->placeholder('-'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('acolhido.nome_completo_paciente')->label('Acolhido')->searchable()->sortable(),
                TextColumn::make('demanda')->label('Demanda')->searchable()->wrap(),
                TextColumn::make('saida_prevista_em')->label('Saída prevista')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('retorno_previsto_em')->label('Retorno previsto')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDemandasAcolhidos::route('/'),
            'create' => CreateDemandaAcolhido::route('/create'),
            'view' => ViewDemandaAcolhido::route('/{record}'),
            'edit' => EditDemandaAcolhido::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return AcolhidoResource::getNavigationGroup();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['demanda', 'acolhido.nome_completo_paciente'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return (string) ($record->demanda ?: 'Registro de demanda assistencial');
    }
}
