<?php

namespace App\Traits;

use App\Support\Concerns\HasActivityLogs;

trait Auditable
{
    use HasActivityLogs;

    /**
     * Return the module name used in activity logs. Override in model when needed.
     */
    public function activityLogModule(): ?string
    {
        return null;
    }

    /**
     * Return a human label for the record used by activity logs. Override in model when needed.
     */
    public function activityLogLabel(): ?string
    {
        return null;
    }

    /**
     * Attributes to ignore in activity logs.
     *
     * @return array<int, string>
     */
    public function activityLogIgnoredAttributes(): array
    {
        return [];
    }

    /**
     * Attributes to mask in activity logs.
     *
     * @return array<int, string>
     */
    public function activityLogMaskedAttributes(): array
    {
        return [];
    }
}
