<?php

namespace App\Filament\Frontend\Resources\BlogPosts\Pages;

use App\Filament\Frontend\Resources\BlogPosts\BlogPostResource;
use App\Support\BlogPostSchema;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ManageRecords;

class ManageBlogPosts extends ManageRecords
{
    protected static string $resource = BlogPostResource::class;

    public function mount(): void
    {
        BlogPostSchema::ensureTableExists();

        parent::mount();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Novo post'),
        ];
    }

    protected function configureEditAction(EditAction $action): void
    {
        $action->label('Editar post');
    }
}
