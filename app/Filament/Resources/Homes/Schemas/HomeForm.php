<?php

namespace App\Filament\Resources\Homes\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Str;

class HomeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Imagem da home')
                    ->description('Envie a imagem principal usada na pagina inicial do site.')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            FileUpload::make('hero_image')
                                ->label('Imagem principal')
                                ->image()
                                ->imageEditor()
                                ->disk('public')
                                ->directory('homes')
                                ->visibility('public')
                                ->downloadable()
                                ->openable()
                                ->maxFiles(1)
                                ->getUploadedFileNameForStorageUsing(
                                    fn (TemporaryUploadedFile $file): string => Str::uuid() . '.' . $file->getClientOriginalExtension()
                                )
                                ->helperText('Essa imagem sera exibida na pagina inicial do cerape.test.'),
                            TextInput::make('title')
                                ->label('Titulo')
                                ->placeholder('Ex.: Hero principal da home')
                                ->maxLength(255),
                            TextInput::make('hero_image_alt')
                                ->label('Texto alternativo')
                                ->placeholder('Ex.: Banner principal da home')
                                ->maxLength(255),
                            TextInput::make('cta_label')
                                ->label('Texto do botao')
                                ->placeholder('Ex.: Saiba mais')
                                ->maxLength(255),
                            TextInput::make('cta_url')
                                ->label('Link do botao')
                                ->placeholder('Ex.: #projects')
                                ->maxLength(255),
                            Textarea::make('subtitle')
                                ->label('Legenda')
                                ->rows(4)
                                ->placeholder('Texto de apoio que acompanha a imagem.')
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
