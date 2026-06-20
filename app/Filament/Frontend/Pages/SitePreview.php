<?php

namespace App\Filament\Frontend\Pages;

use Filament\Pages\Page;
use UnitEnum;

class SitePreview extends Page
{
    protected static ?string $navigationLabel = 'Prévia do site';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $slug = '';

    protected static ?string $title = 'Prévia da página principal';

    protected static string|UnitEnum|null $navigationGroup = 'Frontend';

    protected static bool $shouldRegisterNavigation = true;

    protected string $view = 'filament.frontend.pages.site-preview';

    public function getHomeUrl(): string
    {
        return route('home');
    }
}
