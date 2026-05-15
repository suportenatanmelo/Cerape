<?php

namespace App\Filament\Resources\ArquivosDiarios\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class ArquivosDiarioInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalhes do upload')
                    ->icon('heroicon-o-document')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            TextEntry::make('titulo')
                                ->label('Titulo')
                                ->placeholder('-'),
                            TextEntry::make('updated_at')
                                ->label('Data do arquivo')
                                ->dateTime(),
                            TextEntry::make('upload_arquivo')
                                ->label('Nome salvo')
                                ->formatStateUsing(fn (?string $state): string => $state ? basename($state) : '-')
                                ->placeholder('-'),
                            IconEntry::make('upload_arquivo')
                                ->label('Arquivo disponivel')
                                ->boolean()
                                ->state(fn ($record): bool => filled($record->upload_arquivo)),
                            TextEntry::make('download')
                                ->label('Link do arquivo')
                                ->state(fn ($record): string => filled($record->upload_arquivo) ? Storage::disk('public')->url($record->upload_arquivo) : '-')
                                ->url(fn ($record): ?string => filled($record->upload_arquivo) ? Storage::disk('public')->url($record->upload_arquivo) : null)
                                ->openUrlInNewTab(),
                        ]),
                    ]),
            ]);
    }
}
