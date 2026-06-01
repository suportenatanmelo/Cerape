<?php

namespace App\Filament\Resources\GeradorAtividades\Schemas;

use App\Filament\Resources\GeradorAtividades\GeradorAtividadeResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GeradorAtividadeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Resumo da semana')
                    ->description('Visão geral do planejamento semanal, período e pessoas envolvidas.')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 3,
                        ])->schema([
                            TextEntry::make('titulo')
                                ->label('Título')
                                ->badge()
                                ->color('primary'),
                            TextEntry::make('data_programacao')
                                ->label('Período')
                                ->formatStateUsing(fn ($record): string => GeradorAtividadeResource::getPeriodLabel($record))
                                ->badge()
                                ->color('warning'),
                            TextEntry::make('user.name')
                                ->label('Responsável')
                                ->badge()
                                ->color('info')
                                ->placeholder('-'),
                            TextEntry::make('updated_at')
                                ->label('Última atualização')
                                ->dateTime('d/m/Y H:i')
                                ->badge()
                                ->color('gray'),
                        ]),
                    ]),
                Section::make('Tabela semanal')
                    ->description('Quadro organizado das atividades práticas, demandas e acolhidos por linha.')
                    ->icon('heroicon-o-table-cells')
                    ->columnSpanFull()
                    ->schema([
                        ViewEntry::make('atividades_planejadas')
                            ->hiddenLabel()
                            ->view('filament.resources.gerador-atividades.weekly-activities-table')
                            ->columnSpanFull(),
                    ]),
                Section::make('Observações')
                    ->description('Anotações adicionais da programação.')
                    ->icon('heroicon-o-pencil-square')
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('observacoes')
                            ->label('Observações complementares')
                            ->columnSpanFull()
                            ->html()
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
