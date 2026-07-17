<?php

namespace App\Filament\Forms;

use App\Support\ImageStorageNaming;
use App\Support\SystemBranding;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Support\HtmlString;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class BrandSettingsSchema
{
    /**
     * @return array<int, Section>
     */
    public static function components(): array
    {
        return [
            Section::make('Arquivos da marca')
                ->description('Atualize o logotipo e o favicon usados pelo painel e pelo site público.')
                ->icon('heroicon-o-photo')
                ->schema([
                    Grid::make([
                        'default' => 1,
                        'lg' => 2,
                    ])->schema([
                        Placeholder::make('current_logo_preview')
                            ->label('Logotipo atual')
                            ->content(fn (): HtmlString => new HtmlString(view('filament.forms.branding-asset-preview', [
                                'label' => 'Logotipo do CERAPE',
                                'url' => SystemBranding::logoUrl(),
                                'alt' => 'Logotipo atual do CERAPE',
                                'emptyMessage' => 'Nenhum logotipo salvo ainda.',
                            ])->render())),
                        Placeholder::make('current_favicon_preview')
                            ->label('Favicon atual')
                            ->content(fn (): HtmlString => new HtmlString(view('filament.forms.branding-asset-preview', [
                                'label' => 'Favicon do sistema',
                                'url' => SystemBranding::faviconUrl(),
                                'alt' => 'Favicon atual do CERAPE',
                                'emptyMessage' => 'Nenhum favicon salvo ainda.',
                                'compact' => true,
                            ])->render())),
                    ]),
                    Grid::make([
                        'default' => 1,
                        'lg' => 2,
                    ])->schema([
                        FileUpload::make('logo_path')
                            ->label('Logotipo do CERAPE')
                            ->disk('public')
                            ->directory(ImageStorageNaming::directory('branding'))
                            ->visibility('public')
                            ->image()
                            ->imageEditor()
                            ->imagePreviewHeight('120')
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml'])
                            ->maxSize(2048)
                            ->helperText('Formatos aceitos: PNG, JPG, WEBP ou SVG. Tamanho máximo: 2 MB.')
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file): string => 'logo-cerape.' . strtolower($file->getClientOriginalExtension())
                            ),
                        FileUpload::make('favicon_path')
                            ->label('Favicon')
                            ->disk('public')
                            ->directory(ImageStorageNaming::directory('branding'))
                            ->visibility('public')
                            ->acceptedFileTypes(['image/png', 'image/x-icon', 'image/vnd.microsoft.icon', 'image/svg+xml'])
                            ->maxSize(1024)
                            ->previewable(false)
                            ->openable()
                            ->downloadable()
                            ->helperText('Formatos aceitos: ICO, PNG ou SVG. Tamanho máximo: 1 MB.')
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file): string => 'favicon-cerape.' . strtolower($file->getClientOriginalExtension())
                            ),
                    ]),
                ]),
        ];
    }
}
