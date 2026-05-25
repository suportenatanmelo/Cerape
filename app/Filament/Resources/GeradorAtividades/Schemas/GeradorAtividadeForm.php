<?php

namespace App\Filament\Resources\GeradorAtividades\Schemas;

use App\Filament\Resources\ProntuariosEvolucao\Schemas\ProntuarioEvolucaoForm;
use App\Models\Acolhido;
use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GeradorAtividadeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificacao da programacao')
                    ->description('Organize a rotina do dia selecionando os acolhidos e separando as atividades planejadas para cada periodo.')
                    ->icon('heroicon-o-identification')
                    ->columnSpanFull()
                    ->extraAttributes([
                        'style' => 'max-width: 1100px; margin: 0 auto;',
                    ])
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            TextInput::make('titulo')
                                ->label('Titulo da programacao')
                                ->default('Programacao diaria de atividades')
                                ->maxLength(255)
                                ->required(),
                            DatePicker::make('data_programacao')
                                ->label('Data da programacao')
                                ->native(false)
                                ->default(now())
                                ->required(),
                            Select::make('user_id')
                                ->label('Responsavel')
                                ->options(fn () => User::query()->orderBy('name')->pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->default(fn (): ?int => auth()->id())
                                ->required(),
                            Select::make('acolhidos_ids')
                                ->label('Acolhidos do dia')
                                ->options(fn () => Acolhido::query()->orderBy('nome_completo_paciente')->pluck('nome_completo_paciente', 'id'))
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->required()
                                ->helperText('Selecione os acolhidos que participarao da programacao. Os itens aparecem em formato de tags.'),
                        ]),
                    ]),
                Section::make('Atividades do dia')
                    ->description('Marque as atividades previstas para o turno da manha e para o turno da tarde.')
                    ->icon('heroicon-o-calendar-days')
                    ->columnSpanFull()
                    ->extraAttributes([
                        'style' => 'max-width: 1100px; margin: 0 auto;',
                    ])
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'xl' => 2,
                        ])->schema([
                            Section::make('Turno matutino')
                                ->description('Checklist das atividades previstas para a manha.')
                                ->icon('heroicon-o-sun')
                                ->schema([
                                    CheckboxList::make('atividades_matutinas')
                                        ->label('Atividades matutinas')
                                        ->options(ProntuarioEvolucaoForm::getClinicActivityOptions())
                                        ->columns([
                                            'default' => 1,
                                            'md' => 2,
                                        ])
                                        ->gridDirection('row')
                                        ->bulkToggleable(false)
                                        ->columnSpanFull(),
                                ]),
                            Section::make('Turno vespertino')
                                ->description('Checklist das atividades previstas para a tarde.')
                                ->icon('heroicon-o-moon')
                                ->schema([
                                    CheckboxList::make('atividades_vespertinas')
                                        ->label('Atividades vespertinas')
                                        ->options(ProntuarioEvolucaoForm::getClinicActivityOptions())
                                        ->columns([
                                            'default' => 1,
                                            'md' => 2,
                                        ])
                                        ->gridDirection('row')
                                        ->bulkToggleable(false)
                                        ->columnSpanFull(),
                                ]),
                        ]),
                    ]),
                Section::make('Observacoes complementares')
                    ->description('Use este espaco para registrar combinados, focos terapeuticos ou orientacoes adicionais da equipe.')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->columnSpanFull()
                    ->extraAttributes([
                        'style' => 'max-width: 1100px; margin: 0 auto;',
                    ])
                    ->schema([
                        Textarea::make('observacoes')
                            ->label('Observacoes')
                            ->rows(5)
                            ->placeholder('Descreva observacoes importantes para a execucao das atividades ao longo do dia.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
