<?php

namespace App\Repositories;

use App\Models\CmsContent;
use Illuminate\Database\Eloquent\Collection;

class CmsContentRepository
{
    public function publishedByType(string $type, ?int $limit = null): Collection
    {
        $query = CmsContent::query()
            ->type($type)
            ->published()
            ->orderBy('position')
            ->orderBy('title');

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public function groupedPublished(): array
    {
        return collect(array_keys(CmsContent::TYPES))
            ->mapWithKeys(fn (string $type): array => [$type => $this->publishedByType($type)])
            ->all();
    }
}
