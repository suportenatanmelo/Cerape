<?php

namespace App\Support;

use App\Models\FrontendSetting;
use Illuminate\Support\Facades\Storage;

class SystemBranding
{
    public static function settings(): FrontendSetting
    {
        return FrontendSetting::query()->first() ?? new FrontendSetting();
    }

    public static function brandName(?string $default = 'CERAPE'): string
    {
        return (string) (static::settings()->brand_name ?: $default);
    }

    public static function logoPath(): ?string
    {
        return static::normalizeStoragePath(static::settings()->logo_path);
    }

    public static function faviconPath(): ?string
    {
        return static::normalizeStoragePath(static::settings()->favicon_path);
    }

    public static function logoUrl(?string $default = null): ?string
    {
        return static::storageUrl(static::logoPath(), $default ?? asset('logo.png'));
    }

    public static function faviconUrl(?string $default = null): ?string
    {
        return static::storageUrl(static::faviconPath(), $default ?? asset('logo.png'));
    }

    public static function logoPublicPath(?string $default = 'logo.png'): ?string
    {
        return static::publicPathFromStorage(static::logoPath()) ?? $default;
    }

    public static function faviconPublicPath(?string $default = 'logo.png'): ?string
    {
        return static::publicPathFromStorage(static::faviconPath()) ?? $default;
    }

    public static function storageUrl(?string $path, ?string $default = null): ?string
    {
        if (blank($path)) {
            return $default;
        }

        return Storage::disk('public')->url($path);
    }

    public static function publicPathFromStorage(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return 'storage/' . ltrim((string) $path, '/');
    }

    private static function normalizeStoragePath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return ltrim((string) $path, '/');
    }
}
