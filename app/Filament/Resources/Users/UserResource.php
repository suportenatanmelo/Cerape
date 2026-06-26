<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Concerns\HasNavigationCountBadge;
use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use App\Support\UserRoleManager;
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
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Support\ImageStorageNaming;

class UserResource extends Resource
{
    use HasNavigationCountBadge;

    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::User;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('email', '!=', 'suportenatanmelo@gmail.com');
    }

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
                                ->directory(ImageStorageNaming::directory('user-avatar'))
                                ->visibility('public')
                                ->maxFiles(1)
                                ->helperText('A imagem será salva em documentos/user-avatar e receberá o nome padronizado do sistema.'),
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
                            Select::make('acolhido_id')
                                ->label('Acolhido vinculado')
                                ->relationship('acolhido', 'nome_completo_paciente')
                                ->searchable()
                                ->preload()
                                ->helperText('Preencha para criar um acesso familiar restrito a este acolhido.'),
                            TextInput::make('cpf')
                                ->label('CPF')
                                ->mask('999.999.999-99')
                                ->maxLength(14)
                                ->unique(ignoreRecord: true),
                            DatePicker::make('data_nascimento')
                                ->label('Data de nascimento')
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
                            Toggle::make('active_status')
                                ->label('Usuario ativo')
                                ->helperText('Use para ativar ou desativar este usuario.')
                                ->default(true)
                                ->inline(false),
                            Select::make('roles')
                                ->label('Perfis de acesso')
                                ->options(fn (): array => app(config('permission.models.role'))::query()
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->all())
                                ->multiple()
                                ->default([])
                                ->preload()
                                ->searchable()
                                ->afterStateHydrated(function (Select $component, ?User $record): void {
                                    if ($record instanceof User) {
                                        $component->state(
                                            $record->roles
                                                ->pluck('id')
                                                ->map(fn (mixed $id): string => (string) $id)
                                                ->all()
                                        );
                                    }
                                })
                                ->dehydrateStateUsing(fn (mixed $state): array => collect($state)
                                    ->filter(fn (mixed $role): bool => filled($role))
                                    ->map(fn (mixed $role): string => (string) $role)
                                    ->values()
                                    ->all())
                                ->required(fn (callable $get): bool => filled($get('acolhido_id')))
                                ->helperText('O controle do painel deste usuario sera definido pelo perfil selecionado no Shield.')
                                ->columnSpanFull(),
                            TextInput::make('password')
                                ->label('Senha')
                                ->password()
                                ->revealable()
                                ->required(fn (string $operation): bool => $operation === 'create')
                                ->dehydrated(fn (?string $state): bool => filled($state))
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
                TextEntry::make('acolhido.nome_completo_paciente')
                    ->label('Acolhido vinculado')
                    ->badge()
                    ->color('warning')
                    ->placeholder('-'),
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
                TextEntry::make('active_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Ativo' : 'Inativo')
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
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
                TextColumn::make('acolhido.nome_completo_paciente')
                    ->label('Acolhido vinculado')
                    ->searchable()
                    ->placeholder('-'),
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
                ToggleColumn::make('active_status')
                    ->label('Ativo')
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Perfis de acesso')
                    ->badge()
                    ->separator(',')
                    ->placeholder('Sem perfil')
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
                ViewAction::make()
                    ->label('Visualizar'),
                EditAction::make()
                    ->using(function (Model $record, array $data): Model {
                        return static::updateUserWithRoles($record, $data);
                    }),
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

    public static function getGlobalSearchEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['acolhido', 'roles']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
            'email',
            'cpf',
            'uf',
            'acolhido.nome_completo_paciente',
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return (string) $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Email' => $record->email ?: '-',
            'CPF' => $record->cpf ?: '-',
            'Acolhido' => $record->acolhido?->nome_completo_paciente ?: '-',
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

    public static function createUserWithRoles(array $data): User
    {
        ['attributes' => $attributes, 'roles' => $roles] = UserRoleManager::splitFormData($data);

        /** @var User $user */
        $user = static::getModel()::create($attributes);
        UserRoleManager::syncRoles($user, $roles);

        return $user;
    }

    public static function updateUserWithRoles(Model $record, array $data): Model
    {
        ['attributes' => $attributes, 'roles' => $roles] = UserRoleManager::splitFormData($data);

        $record->update($attributes);

        if ($record instanceof User) {
            UserRoleManager::syncRoles($record, $roles);
        }

        return $record;
    }
}
