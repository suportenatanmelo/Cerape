<?php

namespace App\Filament\Resources\Saudes\Schemas;

use App\Support\PdfImage;
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
                Section::make('Resumo da ficha de saúde')
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
                                ->circular()
                                ->getStateUsing(fn ($record): ?string => PdfImage::publicUrl($record?->acolhido?->avatar)),
                        ]),
                        Section::make('Condições de saúde e tratamento')
                            ->schema([
                                IconEntry::make('faz_tratamento_medico')
                                ->label('Tratamento médico atual')
                                    ->boolean(),
                                TextEntry::make('condicoes_saude')
                                ->label('Condições de saúde')
                                    ->badge()
                                    ->color('info')
                                    ->listWithLineBreaks()
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                            ]),
                        Section::make('Uso e dosagem da medicação')
                            ->schema([
                                IconEntry::make('usa_medicacao_psicoativa')
                                ->label('Uso de medicação psicoativa')
                                    ->boolean(),
                                TextEntry::make('nome_medicacao_psicoativa')
                                    ->label('Nome da medicação psicoativa ou princípio ativo')
                                    ->badge()
                                    ->color('primary')
                                    ->listWithLineBreaks()
                                    ->placeholder('-'),
                                TextEntry::make('dosagem_medicacao_psicoativa')
                                    ->label('Dosagem da medicação psicoativa')
                                    ->badge()
                                    ->color('warning')
                                    ->placeholder('-'),
                                IconEntry::make('prescrito_profissional')
                                ->label('Medicação prescrita por profissional de saúde')
                                    ->boolean(),
                                TextEntry::make('diagnosticado')
                                    ->label('Condições diagnosticadas relacionadas à saúde mental ou uso de substâncias')
                                    ->badge()
                                    ->color('danger')
                                    ->listWithLineBreaks()
                                    ->placeholder('-'),
                            ]),
                        Section::make('Observações clínicas')
                            ->schema([
                                TextEntry::make('medicamentos_em_uso')
                                    ->label('Medicamentos em uso')
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                                TextEntry::make('alergias_restricoes')
                                    ->label('Alergias, restrições ou cuidados especiais')
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                                TextEntry::make('observacoes_clinicas')
                                    ->label('Observações clínicas')
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
