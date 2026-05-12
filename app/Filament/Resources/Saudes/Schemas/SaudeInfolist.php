<?php

namespace App\Filament\Resources\Saudes\Schemas;

use Filament\Infolists\Components\IconEntry;
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
                                ->label('Acolhido'),
                            IconEntry::make('faz_tratamento_medico')
                                ->label('Tratamento medico atual')
                                ->boolean(),
                            TextEntry::make('condicoes_saude')
                                ->label('Condicoes de saude')
                                ->listWithLineBreaks()
                                ->placeholder('-')
                                ->columnSpanFull(),
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
