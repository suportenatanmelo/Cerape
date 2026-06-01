<?php

namespace App\Filament\Resources\AcolhidoGalerias\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class AcolhidoGaleriaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Galeria de imagens do acolhido')
                    ->description('Cadastre álbuns autorizados para exibição no portal da família. Cada álbum respeita a permissão liberada no Shield ACL.')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            Placeholder::make('gallery_flow_notice')
                                ->label('Como funciona')
                                ->content(new HtmlString('Agora cada acolhido pode ter <strong>vários álbuns</strong>. Crie um novo álbum sempre que quiser separar momentos, visitas, atividades ou períodos diferentes no portal da família.'))
                                ->columnSpanFull(),
                            Select::make('acolhido_id')
                                ->label('Acolhido')
                                ->relationship('acolhido', 'nome_completo_paciente')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->helperText('Você pode criar mais de um álbum para o mesmo acolhido.'),
                            Toggle::make('ativo')
                                ->label('Álbum ativo no portal')
                                ->default(true)
                                ->inline(false),
                            TextInput::make('titulo')
                                ->label('Título do álbum')
                                ->required()
                                ->maxLength(150)
                                ->placeholder('Ex.: Visita da família em maio')
                                ->columnSpanFull(),
                            Textarea::make('descricao')
                                ->label('Descrição')
                                ->rows(3)
                                ->maxLength(500)
                                ->placeholder('Mensagem breve para contextualizar este álbum no portal.')
                                ->columnSpanFull(),
                            SpatieMediaLibraryFileUpload::make('gallery')
                                ->label('Imagens do álbum')
                                ->collection('gallery')
                                ->image()
                                ->imageEditor()
                                ->conversion('thumb')
                                ->multiple()
                                ->reorderable()
                                ->appendFiles()
                                ->openable()
                                ->downloadable()
                                ->customProperties(fn (): array => [
                                    'uploaded_at' => now()->toIso8601String(),
                                ])
                                ->maxFiles(50)
                                ->minFiles(1)
                                ->required()
                                ->helperText('Envie várias imagens, reorganize na ordem desejada e mantenha apenas imagens aprovadas para a família. Cada cadastro representa um álbum separado.')
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
