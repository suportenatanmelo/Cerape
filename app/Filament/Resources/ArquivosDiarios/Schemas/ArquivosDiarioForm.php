<?php

namespace App\Filament\Resources\ArquivosDiarios\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ArquivosDiarioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Arquivo para arquivamento')
                    ->description('Centralize os documentos neste modulo de uploads com visual simples e organizado.')
                    ->icon('heroicon-o-folder')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            TextInput::make('titulo')
                                ->label('Titulo do documento')
                                ->placeholder('Ex.: Relatorio mensal, oficio, declaracao')
                                ->maxLength(255)
                                ->required()
                                ->columnSpanFull(),
                            FileUpload::make('upload_arquivo')
                                ->label('Arquivo')
                                ->disk('public')
                                ->directory('arquivos-diarios')
                                ->downloadable()
                                ->openable()
                                ->acceptedFileTypes([
                                    'application/pdf',
                                    'image/png',
                                    'image/jpeg',
                                    'image/jpg',
                                    'application/msword',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                ])
                                ->helperText('O sistema renomeia o arquivo com o padrao ID_DDMMAAAA para facilitar o arquivamento.')
                                ->required(),
                            DateTimePicker::make('updated_at')
                                ->label('Data do arquivo')
                                ->seconds(false)
                                ->default(now())
                                ->required(),
                        ]),
                    ]),
            ]);
    }
}
