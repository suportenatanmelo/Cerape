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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class AcolhidoGaleriaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Galeria de imagens do acolhido')
                    ->description('Cadastre as imagens autorizadas para exibicao no portal da familia. O carrossel so sera mostrado quando a permissao do ACL estiver liberada.')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            Placeholder::make('gallery_flow_notice')
                                ->label('Como funciona')
                                ->content(new HtmlString('Cada acolhido possui uma unica galeria. Para adicionar novas imagens em um acolhido ja cadastrado, use <strong>Gerenciar imagens</strong> na lista e o sistema vai somando tudo no mesmo acervo.'))
                                ->columnSpanFull(),
                            Select::make('acolhido_id')
                                ->label('Acolhido')
                                ->relationship(
                                    'acolhido',
                                    'nome_completo_paciente',
                                    modifyQueryUsing: function (Builder $query, ?Model $record): Builder {
                                        return $query->whereDoesntHave('acolhidoGaleria', function (Builder $galleryQuery) use ($record): void {
                                            if ($record?->exists) {
                                                $galleryQuery->whereKeyNot($record->getKey());
                                            }
                                        });
                                    },
                                )
                                ->searchable()
                                ->preload()
                                ->required()
                                ->helperText('Somente acolhidos sem galeria aparecem aqui. Se o acolhido ja existir na lista, abra o registro dele para adicionar mais imagens.'),
                            Toggle::make('ativo')
                                ->label('Galeria ativa no portal')
                                ->default(true)
                                ->inline(false),
                            TextInput::make('titulo')
                                ->label('Titulo da galeria')
                                ->maxLength(150)
                                ->placeholder('Ex.: Momentos especiais da semana')
                                ->columnSpanFull(),
                            Textarea::make('descricao')
                                ->label('Descricao')
                                ->rows(3)
                                ->maxLength(500)
                                ->placeholder('Mensagem breve para acompanhar as imagens no portal.')
                                ->columnSpanFull(),
                            SpatieMediaLibraryFileUpload::make('gallery')
                                ->label('Imagens da galeria')
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
                                ->helperText('Envie varias imagens, reorganize na ordem desejada e mantenha apenas imagens aprovadas para a familia. As miniaturas sao geradas automaticamente.')
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
