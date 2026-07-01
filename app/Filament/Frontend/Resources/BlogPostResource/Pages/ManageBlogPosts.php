<?php

namespace App\Filament\Frontend\Resources\BlogPostResource\Pages;

use App\Filament\Frontend\Resources\BlogPostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageBlogPosts extends ManageRecords
{
    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
