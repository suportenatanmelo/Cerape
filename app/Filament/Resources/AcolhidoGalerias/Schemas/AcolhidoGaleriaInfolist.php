<?php

namespace App\Filament\Resources\AcolhidoGalerias\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AcolhidoGaleriaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'xl' => 3,
                ])
                    ->schema([
                        Section::make('Resumo do álbum')
                            ->description('Informações principais do álbum e do acolhido em uma visão rápida.')
                            ->icon('heroicon-o-identification')
                            ->columnSpan([
                                'default' => 1,
                                'xl' => 2,
                            ])
                            ->columns([
                                'default' => 1,
                                'md' => 2,
                            ])
                            ->schema([
                                TextEntry::make('acolhido.nome_completo_paciente')
                                    ->label('Acolhido')
                                    ->size('lg')
                                    ->weight('bold')
                                    ->placeholder('-'),
                                TextEntry::make('titulo')
                                    ->label('Álbum')
                                    ->badge()
                                    ->color('primary')
                                    ->placeholder('Galeria sem título'),
                                TextEntry::make('ativo')
                                    ->label('Status')
                                    ->badge()
                                    ->formatStateUsing(fn (bool $state): string => $state ? 'Publicado no portal' : 'Oculto no portal')
                                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
                                TextEntry::make('updated_at')
                                    ->label('Última atualização')
                                    ->dateTime('d/m/Y H:i')
                                    ->badge()
                                    ->color('warning'),
                                TextEntry::make('descricao')
                                    ->label('Descrição')
                                    ->placeholder('Sem descrição cadastrada para este álbum.')
                                    ->columnSpanFull(),
                            ]),
                        Section::make('Panorama visual')
                            ->description('Indicadores que ajudam a entender o ritmo e o volume do álbum.')
                            ->icon('heroicon-o-sparkles')
                            ->compact()
                            ->columnSpan(1)
                            ->schema([
                                TextEntry::make('gallery_count')
                                    ->label('Total de imagens')
                                    ->badge()
                                    ->color('info')
                                    ->getStateUsing(fn ($record): string => (string) $record->galleryCount()),
                                TextEntry::make('gallery_periods_count')
                                    ->label('Momentos registrados')
                                    ->badge()
                                    ->color('success')
                                    ->getStateUsing(fn ($record): string => (string) $record->galleryPeriodsCount()),
                                TextEntry::make('created_at')
                                    ->label('Álbum criado em')
                                    ->dateTime('d/m/Y H:i')
                                    ->badge()
                                    ->color('gray'),
                                IconEntry::make('ativo')
                                    ->label('Disponível para famílias')
                                    ->boolean(),
                            ]),
                    ]),
                Section::make('Biblioteca visual')
                    ->description('Mesmo fluxo do layout atual, agora com mais destaque para a narrativa visual e a leitura das imagens.')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        ViewEntry::make('gallery_preview')
                            ->hiddenLabel()
                            ->view('filament.resources.acolhido-galerias.gallery-timeline')
                            ->viewData(fn ($record): array => [
                                'gallery' => $record,
                                'acolhido' => $record->acolhido,
                                'imageUrls' => $record->galleryUrls(),
                                'galleryTimeline' => $record->galleryTimeline(),
                            ]),
                    ]),
            ]);
    }
}
