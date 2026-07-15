<?php

namespace App\Filament\Resources\AcolhidoVideos\Schemas;

use App\Models\AcolhidoVideo;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class AcolhidoVideoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Video para o portal da familia')
                    ->description('Cadastre links do YouTube para compartilhar orientacoes, registros e conteudos aprovados.')
                    ->icon('heroicon-o-play-circle')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            Select::make('acolhido_id')
                                ->label('Acolhido')
                                ->relationship('acolhido', 'nome_completo_paciente')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Toggle::make('ativo')
                                ->label('Video ativo no portal')
                                ->default(true)
                                ->inline(false),
                            TextInput::make('titulo')
                                ->label('Titulo')
                                ->required()
                                ->maxLength(150)
                                ->placeholder('Ex.: Mensagem da equipe para a familia')
                                ->columnSpanFull(),
                            TextInput::make('youtube_url')
                                ->label('Link do YouTube')
                                ->required()
                                ->url()
                                ->live(onBlur: true)
                                ->placeholder('https://www.youtube.com/watch?v=...')
                                ->helperText('Aceita links youtube.com, youtu.be, shorts ou apenas o ID do video.')
                                ->columnSpanFull(),
                            Placeholder::make('preview')
                                ->label('Pre-visualizacao')
                                ->content(function (Get $get): HtmlString {
                                    $videoId = AcolhidoVideo::extractYoutubeId((string) $get('youtube_url'));

                                    if (blank($videoId)) {
                                        return new HtmlString('<span class="text-sm text-gray-500">Informe um link valido do YouTube para habilitar a pre-visualizacao.</span>');
                                    }

                                    $embedUrl = 'https://www.youtube.com/embed/'.$videoId;

                                    return new HtmlString(
                                        '<div class="overflow-hidden rounded-2xl border border-gray-200 bg-black shadow-sm">'.
                                        '<iframe src="'.e($embedUrl).'" class="aspect-video w-full" allowfullscreen loading="lazy"></iframe>'.
                                        '</div>'
                                    );
                                })
                                ->columnSpanFull(),
                            TextInput::make('ordem')
                                ->label('Ordem')
                                ->numeric()
                                ->default(0)
                                ->minValue(0),
                            Textarea::make('descricao')
                                ->label('Descrição')
                                ->rows(3)
                                ->maxLength(500)
                                ->placeholder('Contextualize o video para a familia.')
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
