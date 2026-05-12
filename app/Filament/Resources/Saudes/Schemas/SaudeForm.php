<?php

namespace App\Filament\Resources\Saudes\Schemas;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SaudeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificacao do registro')
                    ->description('Vincule esta ficha de saude ao acolhido correto.')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            Select::make('acolhido_id')
                                ->label('Acolhido')
                                ->relationship('acolhido', 'nome_completo_paciente')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ]),
                    ]),
                Section::make('Condicoes e diagnosticos informados')
                    ->description('Registre as condicoes de saude relatadas ou observadas usando uma tag para cada item.')
                    ->icon('heroicon-o-heart')
                    ->schema([
                        TagsInput::make('condicoes_saude')
                            ->label('Condicoes de saude')
                            ->placeholder('Digite uma condicao de saude e pressione Enter')
                            ->helperText('Use uma tag para cada condicao clinica relevante.')
                            ->suggestions(self::healthSuggestions())
                            ->splitKeys(['Tab', 'Enter', ','])
                            ->separator(',')
                            ->reorderable()
                            ->nestedRecursiveRules(['distinct'])
                            ->required()
                            ->columnSpanFull(),
                        TagsInput::make('diagnosticado')
                            ->label('Diagnosticos informados')
                            ->placeholder('Digite um diagnostico e pressione Enter')
                            ->helperText('Use uma tag para cada diagnostico ou hipotese clinica informada.')
                            ->suggestions(self::diagnosisSuggestions())
                            ->splitKeys(['Tab', 'Enter', ','])
                            ->separator(',')
                            ->reorderable()
                            ->nestedRecursiveRules(['distinct'])
                            ->columnSpanFull(),
                        Textarea::make('observacoes_clinicas')
                            ->label('Observacoes clinicas')
                            ->placeholder('Descreva sintomas importantes, cuidados necessarios, limitacoes ou informacoes complementares.')
                            ->rows(4)
                            ->maxLength(2000)
                            ->columnSpanFull(),
                    ]),
                Section::make('Acompanhamento atual')
                    ->description('Documente tratamento em andamento e alertas importantes para a equipe.')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            Radio::make('faz_tratamento_medico')
                                ->label('Faz tratamento medico atualmente?')
                                ->boolean('Sim', 'Nao')
                                ->inline()
                                ->live()
                                ->default(false)
                                ->afterStateUpdated(function ($set, mixed $state): void {
                                    if ($state) {
                                        return;
                                    }

                                    $set('medicamentos_em_uso', null);
                                }),
                            Textarea::make('medicamentos_em_uso')
                                ->label('Medicamentos em uso')
                                ->placeholder('Informe medicacoes de uso continuo, dosagens ou orientacoes relevantes.')
                                ->rows(4)
                                ->hidden(fn ($get): bool => ! (bool) $get('faz_tratamento_medico'))
                                ->required(fn ($get): bool => (bool) $get('faz_tratamento_medico'))
                                ->dehydratedWhenHidden(),
                            Textarea::make('alergias_restricoes')
                                ->label('Alergias, restricoes ou cuidados especiais')
                                ->placeholder('Ex.: alergia medicamentosa, restricao alimentar, risco de crise, acompanhamento especifico.')
                                ->rows(4)
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Medicacao psicoativa')
                    ->description('Registre o uso de medicacao psicoativa, o nome das medicacoes, a dosagem e a existencia de prescricao profissional.')
                    ->icon('heroicon-o-beaker')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            Radio::make('usa_medicacao_psicoativa')
                                ->label('Usa medicacao psicoativa?')
                                ->boolean('Sim', 'Nao')
                                ->inline()
                                ->live()
                                ->default(false)
                                ->afterStateUpdated(function ($set, mixed $state): void {
                                    if ($state) {
                                        return;
                                    }

                                    $set('nome_medicacao_psicoativa', null);
                                    $set('dosagem_medicacao_psicoativa', null);
                                    $set('prescrito_profissional', false);
                                }),
                            Radio::make('prescrito_profissional')
                                ->label('Medicacao prescrita por profissional de saude?')
                                ->boolean('Sim', 'Nao')
                                ->inline()
                                ->default(false)
                                ->hidden(fn ($get): bool => ! (bool) $get('usa_medicacao_psicoativa'))
                                ->required(fn ($get): bool => (bool) $get('usa_medicacao_psicoativa'))
                                ->dehydratedWhenHidden(),
                            TagsInput::make('nome_medicacao_psicoativa')
                                ->label('Nome da medicacao psicoativa')
                                ->placeholder('Digite a medicacao e pressione Enter')
                                ->helperText('Use uma tag para cada medicacao psicoativa em uso.')
                                ->suggestions(self::psychotropicMedicationSuggestions())
                                ->splitKeys(['Tab', 'Enter', ','])
                                ->separator(',')
                                ->reorderable()
                                ->nestedRecursiveRules(['distinct'])
                                ->hidden(fn ($get): bool => ! (bool) $get('usa_medicacao_psicoativa'))
                                ->required(fn ($get): bool => (bool) $get('usa_medicacao_psicoativa'))
                                ->dehydratedWhenHidden()
                                ->columnSpanFull(),
                            Textarea::make('dosagem_medicacao_psicoativa')
                                ->label('Dosagem e orientacoes')
                                ->placeholder('Ex.: Clonazepam 2 mg a noite; Fluoxetina 20 mg pela manha.')
                                ->rows(4)
                                ->hidden(fn ($get): bool => ! (bool) $get('usa_medicacao_psicoativa'))
                                ->required(fn ($get): bool => (bool) $get('usa_medicacao_psicoativa'))
                                ->dehydratedWhenHidden()
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }

    /**
     * @return array<int, string>
     */
    public static function healthSuggestions(): array
    {
        return [
            'Pressao Alta',
            'Diabetes',
            'Doencas Cardiacas',
            'Derrame / Isquemia (AVC)',
            'Epilepsia ou convulsoes',
            'Cancer',
            'HIV/AIDS',
            'Outras DSTs',
            'Tuberculose',
            'Hepatite A',
            'Hepatite B',
            'Hepatite C',
            'Cirrose ou outra doenca cronica do figado',
            'Doenca renal cronica',
            'Problema respiratorio cronico',
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function diagnosisSuggestions(): array
    {
        return [
            'Ansiedade',
            'Depressao',
            'Esquizofrenia',
            'Transtorno Afetivo Bipolar',
            'Transtorno do Panico',
            'Transtorno de Personalidade',
            'Dependencia quimica',
            'Insomnia cronica',
            'Deficiencia intelectual',
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function psychotropicMedicationSuggestions(): array
    {
        return [
            'Amitriptilina',
            'Clonazepam',
            'Diazepam',
            'Fluoxetina',
            'Haloperidol',
            'Quetiapina',
            'Risperidona',
            'Sertralina',
            'Valproato de Sodio',
        ];
    }
}
