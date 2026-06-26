<?php

namespace App\Filament\Frontend\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class MediaManager extends Page
{
    protected static ?string $navigationLabel = 'Mídia';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static ?string $slug = 'midia';

    protected static ?string $title = 'Mídia do site';

    protected static string|UnitEnum|null $navigationGroup = 'Mídia';

    protected static bool $shouldRegisterNavigation = true;

    protected string $view = 'filament.frontend.pages.media-manager';

    public function getMediaGroups(): array
    {
        return [
            'Galeria' => $this->collectFiles('imagens/galeria'),
            'Blog' => $this->collectFiles('imagens/blog'),
            'Equipe' => $this->collectFiles('imagens/equipe_tecnica'),
            'Avatar' => $this->collectFiles('imagens/avatar'),
            'Receituário' => $this->collectFiles('imagens/receituario'),
        ];
    }

    /**
     * @return array<int, array{path:string,url:string,name:string,size:int}>
     */
    protected function collectFiles(string $directory): array
    {
        return collect(Storage::disk('public')->files($directory))
            ->map(function (string $path): array {
                return [
                    'path' => $path,
                    'url' => Storage::disk('public')->url($path),
                    'name' => basename($path),
                    'size' => Storage::disk('public')->size($path),
                ];
            })
            ->sortBy('name')
            ->values()
            ->all();
    }
}
