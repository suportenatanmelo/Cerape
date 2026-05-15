<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::User;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Perfil')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            FileUpload::make('avatar')
                                ->label('Imagem')
                                ->image()
                                ->imageEditor()
                                ->avatar()
                                ->disk('public')
                                ->directory('users/avatars')
                                ->visibility('public')
                                ->maxFiles(1)
                                ->getUploadedFileNameForStorageUsing(
                                    fn(TemporaryUploadedFile $file): string => Str::uuid() . '.' . $file->getClientOriginalExtension()
                                ),
                            TextInput::make('name')
                                ->label('Nome completo')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),
                            TextInput::make('cpf')
                                ->label('CPF')
                                ->mask('999.999.999-99')
                                ->maxLength(14)
                                ->unique(ignoreRecord: true),
                            DatePicker::make('data_nascimento')
                                ->label('Data de nascimento')
                                ->native(false)
                                ->maxDate(now()),
                            Select::make('uf')
                                ->label('UF')
                                ->searchable()
                                ->options(self::getBrazilianStates()),
                            TextInput::make('endereco')
                                ->label('Endereco')
                                ->maxLength(255)
                                ->columnSpanFull(),
                            TextInput::make('nacionalidade')
                                ->label('Nacionalidade')
                                ->default('Brasileira')
                                ->maxLength(255),
                            DateTimePicker::make('email_verified_at')
                                ->label('Email verificado em'),
                            Select::make('roles')
                                ->label('Perfis de acesso')
                                ->relationship('roles', 'name')
                                ->multiple()
                                ->preload()
                                ->searchable()
                                ->columnSpanFull(),
                            TextInput::make('password')
                                ->label('Senha')
                                ->password()
                                ->revealable()
                                ->required(fn(string $operation): bool => $operation === 'create')
                                ->dehydrated(fn(?string $state): bool => filled($state))
                                ->maxLength(255),
                        ]),
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('avatar')
                    ->label('Imagem')
                    ->disk('public')
                    ->circular()
                    ->height(96)
                    ->width(96),
                TextEntry::make('name')
                    ->label('Nome completo')
                    ->badge()
                    ->color('primary'),
                TextEntry::make('email')
                    ->label('Email')
                    ->badge()
                    ->color('info'),
                TextEntry::make('cpf')
                    ->label('CPF')
                    ->badge()
                    ->color('gray')
                    ->placeholder('-'),
                TextEntry::make('endereco')
                    ->label('Endereco')
                    ->placeholder('-'),
                TextEntry::make('uf')
                    ->label('UF')
                    ->badge()
                    ->color('warning')
                    ->placeholder('-'),
                TextEntry::make('nacionalidade')
                    ->label('Nacionalidade')
                    ->badge()
                    ->color('success')
                    ->placeholder('-'),
                TextEntry::make('data_nascimento')
                    ->label('Data de nascimento')
                    ->date()
                    ->badge()
                    ->color('info')
                    ->placeholder('-'),
                TextEntry::make('email_verified_at')
                    ->label('Email verificado em')
                    ->dateTime()
                    ->badge()
                    ->color('success')
                    ->placeholder('-'),
                TextEntry::make('roles.name')
                    ->label('Perfis de acesso')
                    ->badge()
                    ->separator(',')
                    ->color('warning')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->badge()
                    ->color('gray')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->badge()
                    ->color('warning')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Imagem')
                    ->disk('public')
                    ->circular(),
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('cpf')
                    ->label('CPF')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('uf')
                    ->label('UF')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('data_nascimento')
                    ->label('Nascimento')
                    ->date()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('email_verified_at')
                    ->label('Email verificado em')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Perfis de acesso')
                    ->badge()
                    ->separator(',')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUsers::route('/'),
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function getBrazilianStates(): array
    {
        return [
            'AC' => 'Acre',
            'AL' => 'Alagoas',
            'AP' => 'Amapa',
            'AM' => 'Amazonas',
            'BA' => 'Bahia',
            'CE' => 'Ceara',
            'DF' => 'Distrito Federal',
            'ES' => 'Espirito Santo',
            'GO' => 'Goias',
            'MA' => 'Maranhao',
            'MT' => 'Mato Grosso',
            'MS' => 'Mato Grosso do Sul',
            'MG' => 'Minas Gerais',
            'PA' => 'Para',
            'PB' => 'Paraiba',
            'PR' => 'Parana',
            'PE' => 'Pernambuco',
            'PI' => 'Piaui',
            'RJ' => 'Rio de Janeiro',
            'RN' => 'Rio Grande do Norte',
            'RS' => 'Rio Grande do Sul',
            'RO' => 'Rondonia',
            'RR' => 'Roraima',
            'SC' => 'Santa Catarina',
            'SP' => 'Sao Paulo',
            'SE' => 'Sergipe',
            'TO' => 'Tocantins',
        ];
    }
}
