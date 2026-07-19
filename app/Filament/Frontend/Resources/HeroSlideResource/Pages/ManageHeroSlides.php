<?php

namespace App\Filament\Frontend\Resources\HeroSlideResource\Pages;

use App\Filament\Frontend\Pages\ClearHeroImages;
use App\Filament\Frontend\Pages\HeroSlideTrash;
use App\Filament\Frontend\Resources\HeroSlideResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageHeroSlides extends ManageRecords
{
    protected static string $resource = HeroSlideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('lixeira')
                    ->label('Lixeira')
                    ->icon('heroicon-o-trash')
                    ->url(fn (): string => HeroSlideTrash::getUrl()),
                Action::make('limpar_imagens')
                    ->label('Limpar imagens')
                    ->icon('heroicon-o-photo')
                    ->url(fn (): string => ClearHeroImages::getUrl()),
            ])->label('Ferramentas'),
            CreateAction::make(),
        ];
    }
}
