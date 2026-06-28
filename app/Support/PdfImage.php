<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class PdfImage
{
    public static function publicUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $path = trim((string) $path);

        if (str_starts_with($path, 'data:') || preg_match('#^https?://#i', $path) === 1 || str_starts_with($path, '//')) {
            return $path;
        }

        $path = self::resolvePublicPath($path);

        if (blank($path)) {
            return null;
        }

        $disk = Storage::disk('public');

        if ($disk->exists($path)) {
            return url('/storage-media/' . ltrim($path, '/'));
        }

        $publicAbsolutePath = public_path($path);

        if (is_file($publicAbsolutePath) && is_readable($publicAbsolutePath)) {
            return asset($path);
        }

        if (str_starts_with($path, 'storage/')) {
            $storageAbsolutePath = storage_path('app/public/' . substr($path, 8));

            if (is_file($storageAbsolutePath) && is_readable($storageAbsolutePath)) {
                return url('/storage-media/' . ltrim($path, '/'));
            }
        }

        return null;
    }

    public static function publicDataUri(string $relativePath): ?string
    {
        $relativePath = self::resolvePublicPath($relativePath);

        if (blank($relativePath)) {
            return null;
        }

        $absolutePath = public_path($relativePath);

        if (! is_file($absolutePath) && str_starts_with($relativePath, 'storage/')) {
            $absolutePath = storage_path('app/public/' . substr($relativePath, 8));
        }

        if (! is_file($absolutePath) || ! is_readable($absolutePath)) {
            return null;
        }

        $mimeType = mime_content_type($absolutePath) ?: 'image/png';

        return 'data:' . $mimeType . ';base64,' . base64_encode((string) file_get_contents($absolutePath));
    }

    public static function resolvePublicPath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $path = trim((string) $path);

        if (preg_match('#^https?://#i', $path) === 1) {
            $parsedPath = parse_url($path, PHP_URL_PATH);

            if (is_string($parsedPath) && $parsedPath !== '') {
                $path = ltrim($parsedPath, '/');
            }
        }

        $candidates = array_values(array_unique(array_filter([
            ltrim($path, '/'),
            $path,
            str_starts_with($path, 'storage/') ? substr($path, 8) : null,
            str_starts_with($path, 'public/') ? substr($path, 7) : null,
        ])));

        $disk = Storage::disk('public');

        foreach ($candidates as $candidate) {
            if ($disk->exists($candidate) || is_file(public_path($candidate))) {
                return $candidate;
            }

            if (str_starts_with($candidate, 'storage/')) {
                $storageAbsolutePath = storage_path('app/public/' . substr($candidate, 8));

                if (is_file($storageAbsolutePath)) {
                    return $candidate;
                }
            }
        }

        return ltrim($path, '/');
    }
}
