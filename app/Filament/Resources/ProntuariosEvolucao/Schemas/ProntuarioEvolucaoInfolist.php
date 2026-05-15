<?php

namespace App\Filament\Resources\ProntuariosEvolucao\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProntuarioEvolucaoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Cabecalho do prontuario')
                    ->description('Identificacao do acolhido e momento em que esta evolucao foi registrada.')
                    ->icon('heroicon-o-clipboard-document-check')
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
                                ->hidden(fn ($record) => blank($record?->acolhido?->avatar)),
                            TextEntry::make('user.name')
                                ->label('Registrado por')
                                ->badge()
                                ->color('primary')
                                ->placeholder('-'),
                            TextEntry::make('data_prontuario')
                                ->label('Data e hora do prontuario')
                                ->dateTime('d/m/Y H:i')
                                ->badge()
                                ->color('warning'),
                            TextEntry::make('created_at')
                                ->label('Lançado no sistema em')
                                ->dateTime('d/m/Y H:i')
                                ->badge()
                                ->color('warning'),
                        ]),
                    ]),
                Section::make('Evolucao registrada')
                    ->description('Conteudo progressivo e estruturado do prontuario, com textos, imagens e documentos anexados.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        TextEntry::make('conteudo')
                            ->label('Conteudo')
                            ->formatStateUsing(fn (?string $state): string => str($state ?? '')->sanitizeHtml())
                            ->html()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
