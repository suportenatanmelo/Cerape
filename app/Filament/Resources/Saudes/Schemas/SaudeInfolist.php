<?php

namespace App\Filament\Resources\Saudes\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SaudeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Resumo da ficha de saude')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            TextEntry::make('acolhido.nome_completo_paciente')
                                ->label('Acolhido')
                                ->badge()
                                ->color('primary'),
                            ImageEntry::make('acolhido.avatar')
                                ->label('Foto do acolhido')
                                ->circular(),
                        ]),
                        Section::make('Condicoes de saude e tratamento')
                            ->schema([
                                IconEntry::make('faz_tratamento_medico')
                                    ->label('Tratamento medico atual')
                                    ->boolean(),
                                TextEntry::make('condicoes_saude')
                                    ->label('Condicoes de saude')
                                    ->badge()
                                    ->color('info')
                                    ->listWithLineBreaks()
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                            ]),
                        Section::make('Uso e dosagem da medicacao')
                            ->schema([
                                IconEntry::make('usa_medicacao_psicoativa')
                                    ->label('Uso de medicacao psicoativa')
                                    ->boolean(),
                                TextEntry::make('nome_medicacao_psicoativa')
                                    ->label('Nome da medicacao psicoativa ou principio ativo')
                                    ->badge()
                                    ->color('primary')
                                    ->listWithLineBreaks()
                                    ->placeholder('-'),
                                TextEntry::make('dosagem_medicacao_psicoativa')
                                    ->label('Dosagem da medicacao psicoativa')
                                    ->badge()
                                    ->color('warning')
                                    ->placeholder('-'),
                                IconEntry::make('prescrito_profissional')
                                    ->label('Medicacao prescrita por profissional de saude')
                                    ->boolean(),
                                TextEntry::make('diagnosticado')
                                    ->label('Condicoes diagnosticadas relacionadas a saude mental ou uso de substancias')
                                    ->badge()
                                    ->color('danger')
                                    ->listWithLineBreaks()
                                    ->placeholder('-'),
                            ]),
                        Section::make('Observacoes clinicas')
                            ->schema([
                                TextEntry::make('medicamentos_em_uso')
                                    ->label('Medicamentos em uso')
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                                TextEntry::make('alergias_restricoes')
                                    ->label('Alergias, restricoes ou cuidados especiais')
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                                TextEntry::make('observacoes_clinicas')
                                    ->label('Observacoes clinicas')
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
