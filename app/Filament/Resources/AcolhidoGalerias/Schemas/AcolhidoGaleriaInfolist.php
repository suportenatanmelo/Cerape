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
                        Section::make('Resumo do album')
                            ->description('Informacoes principais do album e do acolhido em uma visao rapida.')
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
                                    ->label('Album')
                                    ->badge()
                                    ->color('primary')
                                    ->placeholder('Galeria sem titulo'),
                                TextEntry::make('ativo')
                                    ->label('Status')
                                    ->badge()
                                    ->formatStateUsing(fn (bool $state): string => $state ? 'Publicado no portal' : 'Oculto no portal')
                                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
                                TextEntry::make('updated_at')
                                    ->label('Ultima atualizacao')
                                    ->dateTime('d/m/Y H:i')
                                    ->badge()
                                    ->color('warning'),
                                TextEntry::make('descricao')
                                    ->label('Descricao')
                                    ->placeholder('Sem descricao cadastrada para este album.')
                                    ->columnSpanFull(),
                            ]),
                        Section::make('Panorama visual')
                            ->description('Indicadores que ajudam a entender o ritmo e o volume do album.')
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
                                    ->label('Album criado em')
                                    ->dateTime('d/m/Y H:i')
                                    ->badge()
                                    ->color('gray'),
                                IconEntry::make('ativo')
                                    ->label('Disponivel para familias')
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
