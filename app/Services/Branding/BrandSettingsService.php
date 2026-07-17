<?php

namespace App\Services\Branding;

use App\Models\FrontendSetting;
use App\Support\ImageStorageNaming;

class BrandSettingsService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function update(array $data): FrontendSetting
    {
        $settings = FrontendSetting::query()->firstOrNew([]);

        $originalLogoPath = $settings->logo_path;
        $originalFaviconPath = $settings->favicon_path;

        $settings->fill($data);
        $settings->save();

        if ($originalLogoPath !== $settings->logo_path) {
            ImageStorageNaming::removeStoredPath($originalLogoPath);
        }

        if ($originalFaviconPath !== $settings->favicon_path) {
            ImageStorageNaming::removeStoredPath($originalFaviconPath);
        }

        return $settings;
    }
}
