<?php

namespace App\Filament\Resources\AcolhidoGalerias\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AcolhidoGaleriaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Resumo da galeria')
                    ->icon('heroicon-o-identification')
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        TextEntry::make('acolhido.nome_completo_paciente')
                            ->label('Acolhido')
                            ->weight('bold'),
                        TextEntry::make('titulo')
                            ->label('Titulo')
                            ->placeholder('-'),
                        IconEntry::make('ativo')
                            ->label('Galeria ativa no portal')
                            ->boolean(),
                        TextEntry::make('updated_at')
                            ->label('Atualizado em')
                            ->dateTime(),
                        TextEntry::make('descricao')
                            ->label('Descricao')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
                Section::make('Image Library')
                    ->description('Mesma experiencia visual do portal da familia, com navegacao horizontal e ampliacao das imagens.')
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
