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
                    ->description('Visao geral do planejamento semanal, periodo e pessoas envolvidas.')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 3,
                        ])->schema([
                            TextEntry::make('titulo')
                                ->label('Titulo')
                                ->badge()
                                ->color('primary'),
                            TextEntry::make('data_programacao')
                                ->label('Periodo')
                                ->formatStateUsing(fn ($record): string => GeradorAtividadeResource::getPeriodLabel($record))
                                ->badge()
                                ->color('warning'),
                            TextEntry::make('user.name')
                                ->label('Responsavel')
                                ->badge()
                                ->color('info')
                                ->placeholder('-'),
                            TextEntry::make('atividades_planejadas')
                                ->label('Quantidade de atividades')
                                ->formatStateUsing(fn ($record): string => (string) GeradorAtividadeResource::getPlannedActivitiesCount($record))
                                ->badge()
                                ->color('success'),
                            TextEntry::make('acolhidos_ids')
                                ->label('Quantidade de acolhidos')
                                ->formatStateUsing(fn ($record): string => (string) count($record->acolhidos_ids ?? []))
                                ->badge()
                                ->color('primary'),
                            TextEntry::make('updated_at')
                                ->label('Ultima atualizacao')
                                ->dateTime('d/m/Y H:i')
                                ->badge()
                                ->color('gray'),
                        ]),
                    ]),
                Section::make('Tabela semanal')
                    ->description('Quadro organizado das atividades praticas, demandas e acolhidos por linha.')
                    ->icon('heroicon-o-table-cells')
                    ->columnSpanFull()
                    ->schema([
                        ViewEntry::make('atividades_planejadas')
                            ->hiddenLabel()
                            ->view('filament.resources.gerador-atividades.weekly-activities-table')
                            ->columnSpanFull(),
                    ]),
                Section::make('Observacoes')
                    ->description('Anotacoes adicionais da programacao.')
                    ->icon('heroicon-o-pencil-square')
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('observacoes')
                            ->label('Observacoes complementares')
                            ->columnSpanFull()
                            ->html()
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
