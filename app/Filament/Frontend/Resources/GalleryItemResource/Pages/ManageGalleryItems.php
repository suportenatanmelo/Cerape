<?php

namespace App\Filament\Frontend\Resources\GalleryItemResource\Pages;

use App\Filament\Frontend\Resources\GalleryItemResource;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ManageGalleryItems extends ManageRecords
{
    protected static string $resource = GalleryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function handleRecordCreation(array $data): Model
    {
        $paths = Arr::wrap($data['image_paths'] ?? []);
        unset($data['image_paths']);

        $lastRecord = null;

        foreach ($paths as $path) {
            $record = new GalleryItem();
            $record->fill($data);
            $record->image_path = $path;
            $record->title = filled($data['title'] ?? null)
                ? (string) $data['title']
                : Str::title(basename((string) $path, '.' . pathinfo((string) $path, PATHINFO_EXTENSION)));
            $record->caption = $data['caption'] ?? null;
            $record->save();

            $lastRecord = $record;
        }

        return $lastRecord ?? new GalleryItem();
    }
}
