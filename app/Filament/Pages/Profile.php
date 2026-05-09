<?php

namespace App\Filament\Pages;

use Filament\Auth\Pages\EditProfile;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Profile extends EditProfile
{
    protected static ?string $navigationLabel = 'Meu perfil';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static ?int $navigationSort = 99;

    public static function getLabel(): string
    {
        return 'Meu perfil';
    }

    public function getTitle(): string | Htmlable
    {
        return 'Meu perfil';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'lg' => 3,
                ])->schema([
                    Section::make('Foto do perfil')
                        ->description('Imagem usada para identificar o usuario no sistema.')
                        ->icon('heroicon-o-camera')
                        ->schema([
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
                                    fn (TemporaryUploadedFile $file): string => Str::uuid() . '.' . $file->getClientOriginalExtension()
                                ),
                        ]),
                    Section::make('Dados de acesso')
                        ->description('Informacoes principais da conta.')
                        ->icon('heroicon-o-lock-closed')
                        ->columnSpan([
                            'lg' => 2,
                        ])
                        ->schema([
                            Grid::make([
                                'default' => 1,
                                'md' => 2,
                            ])->schema([
                                $this->getNameFormComponent()
                                    ->label('Nome completo'),
                                $this->getEmailFormComponent()
                                    ->label('Email'),
                            ]),
                        ]),
                ]),
                Section::make('Dados pessoais')
                    ->description('Informacoes complementares para manter o cadastro profissional e atualizado.')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            TextInput::make('cpf')
                                ->label('CPF')
                                ->mask('999.999.999-99')
                                ->maxLength(14)
                                ->unique(ignoreRecord: true),
                            DatePicker::make('data_nascimento')
                                ->label('Data de nascimento')
                                ->native(false)
                                ->maxDate(now()),
                            TextInput::make('endereco')
                                ->label('Endereco')
                                ->maxLength(255)
                                ->columnSpanFull(),
                            Select::make('uf')
                                ->label('UF')
                                ->searchable()
                                ->options(self::getBrazilianStates()),
                            TextInput::make('nacionalidade')
                                ->label('Nacionalidade')
                                ->default('Brasileira')
                                ->maxLength(255),
                        ]),
                    ]),
                Section::make('Alterar senha')
                    ->description('Preencha apenas se quiser definir uma nova senha.')
                    ->icon('heroicon-o-key')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 3,
                        ])->schema([
                            $this->getPasswordFormComponent()
                                ->label('Nova senha'),
                            $this->getPasswordConfirmationFormComponent()
                                ->label('Confirmar nova senha'),
                            $this->getCurrentPasswordFormComponent()
                                ->label('Senha atual'),
                        ]),
                    ]),
            ]);
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
