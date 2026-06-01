<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class PdfImage
{
    public static function storageDataUri(?string $path): ?string
    {
        $path = self::resolveStoragePath($path);

        if (blank($path)) {
            return null;
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            return null;
        }

        $absolutePath = $disk->path($path);

        if (! is_file($absolutePath) || ! is_readable($absolutePath)) {
            return null;
        }

        $mimeType = $disk->mimeType($path) ?: mime_content_type($absolutePath) ?: 'image/jpeg';

        return 'data:' . $mimeType . ';base64,' . base64_encode((string) file_get_contents($absolutePath));
    }

    public static function publicDataUri(string $relativePath): ?string
    {
        $relativePath = ltrim($relativePath, '/');
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

    public static function resolveStoragePath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $path = trim((string) $path);

        if (str_starts_with($path, 'data:')) {
            return null;
        }

        if (preg_match('#^https?://#i', $path) === 1) {
            $parsedPath = parse_url($path, PHP_URL_PATH);

            if (is_string($parsedPath) && $parsedPath !== '') {
                $path = ltrim($parsedPath, '/');
            }
        }

        $candidates = array_values(array_unique(array_filter([
            $path,
            ltrim($path, '/'),
            str_starts_with($path, 'storage/') ? substr($path, 8) : null,
            str_starts_with($path, 'public/') ? substr($path, 7) : null,
            'acolhidos/avatars/' . basename($path),
            'users/avatars/' . basename($path),
            'avatars/' . basename($path),
        ])));

        $disk = Storage::disk('public');

        foreach ($candidates as $candidate) {
            if ($disk->exists($candidate)) {
                return $candidate;
            }
        }

        return $path;
    }
}
