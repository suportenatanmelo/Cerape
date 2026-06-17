<?php

namespace App\Filament\Frontend\Resources\Testimonials\Schemas;

use App\Support\ImageStorageNaming;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Depoimento')
                    ->description('Crie um card curto, visual e fácil de trocar no painel.')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            TextInput::make('name')
                                ->label('Nome')
                                ->required()
                                ->live(onBlur: true)
                                ->maxLength(255)
                                ->placeholder('Ex.: Família acolhida'),
                            TextInput::make('role')
                                ->label('Perfil')
                                ->placeholder('Ex.: Cuidado e acompanhamento')
                                ->maxLength(255),
                            Textarea::make('summary')
                                ->label('Resumo')
                                ->required()
                                ->rows(3)
                                ->placeholder('Resumo curto que aparece no card da home.')
                                ->columnSpanFull(),
                            TextInput::make('image_alt')
                                ->label('Descrição da imagem')
                                ->placeholder('Ex.: Pessoa em atendimento')
                                ->maxLength(255),
                            TextInput::make('sort_order')
                                ->label('Ordem')
                                ->numeric()
                                ->default(0)
                                ->helperText('Menor número aparece primeiro.')
                                ->maxLength(10),
                            Toggle::make('is_active')
                                ->label('Ativo')
                                ->default(true)
                                ->inline(false),
                        ]),
                    ]),
                Section::make('Imagem')
                    ->description('A imagem pequena ajuda o card horizontal a ficar mais visual.')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            FileUpload::make('image')
                                ->label('Imagem do depoimento')
                                ->image()
                                ->imageEditor()
                                ->disk('public')
                                ->directory(ImageStorageNaming::datedDirectory('frontend/testimonials'))
                                ->visibility('public')
                                ->downloadable()
                                ->openable()
                                ->maxFiles(1)
                                ->maxSize(4096)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->getUploadedFileNameForStorageUsing(
                                    fn (TemporaryUploadedFile $file, Get $get): string => ImageStorageNaming::filename(
                                        $file,
                                        'frontend-testimonial',
                                        (string) $get('name'),
                                    )
                                ),
                        ]),
                    ]),
            ]);
    }
}
