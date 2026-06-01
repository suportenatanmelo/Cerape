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
                Section::make('Identificação do registro')
                    ->description('Vincule esta ficha de saúde ao acolhido correto.')
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
                Section::make('Condições e diagnósticos informados')
                    ->description('Registre as condições de saúde relatadas ou observadas usando uma tag para cada item.')
                    ->icon('heroicon-o-heart')
                    ->schema([
                        TagsInput::make('condicoes_saude')
                            ->label('Condições de saúde')
                            ->placeholder('Digite uma condição de saúde e pressione Enter')
                            ->helperText('Use uma tag para cada condição clínica relevante.')
                            ->suggestions(self::healthSuggestions())
                            ->splitKeys(['Tab', 'Enter', ','])
                            ->separator(',')
                            ->reorderable()
                            ->nestedRecursiveRules(['distinct'])
                            ->required()
                            ->columnSpanFull(),
                        TagsInput::make('diagnosticado')
                            ->label('Diagnósticos informados')
                            ->placeholder('Digite um diagnóstico e pressione Enter')
                            ->helperText('Use uma tag para cada diagnóstico ou hipótese clínica informada.')
                            ->suggestions(self::diagnosisSuggestions())
                            ->splitKeys(['Tab', 'Enter', ','])
                            ->separator(',')
                            ->reorderable()
                            ->nestedRecursiveRules(['distinct'])
                            ->columnSpanFull(),
                        Textarea::make('observacoes_clinicas')
                            ->label('Observações clínicas')
                            ->placeholder('Descreva sintomas importantes, cuidados necessários, limitações ou informações complementares.')
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
                                ->label('Faz tratamento médico atualmente?')
                                ->boolean('Sim', 'Não')
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
                                ->placeholder('Informe medicações de uso contínuo, dosagens ou orientações relevantes.')
                                ->rows(4)
                                ->hidden(fn ($get): bool => ! (bool) $get('faz_tratamento_medico'))
                                ->required(fn ($get): bool => (bool) $get('faz_tratamento_medico'))
                                ->dehydratedWhenHidden(),
                            Textarea::make('alergias_restricoes')
                            ->label('Alergias, restrições ou cuidados especiais')
                            ->placeholder('Ex.: alergia medicamentosa, restrição alimentar, risco de crise, acompanhamento específico.')
                                ->rows(4)
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Medicação psicoativa')
                    ->description('Registre o uso de medicação psicoativa, o nome das medicações, a dosagem e a existência de prescrição profissional.')
                    ->icon('heroicon-o-beaker')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            Radio::make('usa_medicacao_psicoativa')
                                ->label('Usa medicação psicoativa?')
                                ->boolean('Sim', 'Não')
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
                                ->label('Medicação prescrita por profissional de saúde?')
                                ->boolean('Sim', 'Não')
                                ->inline()
                                ->default(false)
                                ->hidden(fn ($get): bool => ! (bool) $get('usa_medicacao_psicoativa'))
                                ->required(fn ($get): bool => (bool) $get('usa_medicacao_psicoativa'))
                                ->dehydratedWhenHidden(),
                            TagsInput::make('nome_medicacao_psicoativa')
                                ->label('Nome da medicação psicoativa')
                                ->placeholder('Digite a medicação e pressione Enter')
                                ->helperText('Use uma tag para cada medicação psicoativa em uso.')
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
                                ->label('Dosagem e orientações')
                                ->placeholder('Ex.: Clonazepam 2 mg à noite; Fluoxetina 20 mg pela manhã.')
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
