<?php

namespace App\Filament\Resources\GeradorAtividades\Schemas;

use App\Filament\Resources\GeradorAtividades\GeradorAtividadeResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GeradorAtividadeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Resumo da programacao')
                    ->description('Visao geral da rotina planejada para os acolhidos selecionados.')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            TextEntry::make('titulo')
                                ->label('Titulo')
                                ->badge()
                                ->color('primary'),
                            TextEntry::make('data_programacao')
                                ->label('Data')
                                ->date('d/m/Y')
                                ->badge()
                                ->color('warning'),
                            TextEntry::make('user.name')
                                ->label('Responsavel')
                                ->badge()
                                ->color('info')
                                ->placeholder('-'),
                            TextEntry::make('acolhidos_ids')
                                ->label('Acolhidos selecionados')
                                ->formatStateUsing(fn (?array $state): string => GeradorAtividadeResource::formatAcolhidos($state))
                                ->columnSpanFull()
                                ->placeholder('-'),
                            TextEntry::make('atividades_matutinas')
                                ->label('Atividades matutinas')
                                ->formatStateUsing(fn (?array $state): string => GeradorAtividadeResource::formatActivities($state))
                                ->columnSpanFull()
                                ->placeholder('-'),
                            TextEntry::make('atividades_vespertinas')
                                ->label('Atividades vespertinas')
                                ->formatStateUsing(fn (?array $state): string => GeradorAtividadeResource::formatActivities($state))
                                ->columnSpanFull()
                                ->placeholder('-'),
                        ]),
                    ]),
                Section::make('Observacoes')
                    ->description('Anotacoes adicionais da programacao.')
                    ->icon('heroicon-o-pencil-square')
                    ->schema([
                        TextEntry::make('observacoes')
                            ->label('Observacoes complementares')
                            ->columnSpanFull()
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
