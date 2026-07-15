<?php

namespace App\Support\Concerns;

trait HasActivityLogs
{
    public function activityLogModule(): ?string
    {
        return null;
    }

    public function activityLogLabel(): ?string
    {
        return null;
    }

    public function activityLogIgnoredAttributes(): array
    {
        return [];
    }

    public function activityLogMaskedAttributes(): array
    {
        return [];
    }
}
