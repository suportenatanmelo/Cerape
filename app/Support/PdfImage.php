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

    public static function storageDataUri(?string $path): ?string
    {
        $candidates = self::storageCandidates($path);

        if ($candidates === []) {
            return null;
        }

        foreach ([Storage::disk('public'), Storage::disk('local')] as $disk) {
            foreach ($candidates as $candidate) {
                if (! $disk->exists($candidate)) {
                    continue;
                }

                $absolutePath = $disk->path($candidate);

                if (! is_file($absolutePath) || ! is_readable($absolutePath)) {
                    continue;
                }

                $mimeType = $disk->mimeType($candidate) ?: mime_content_type($absolutePath) ?: 'image/jpeg';

                if (! self::canEmbedInDompdf($mimeType)) {
                    continue;
                }

                return 'data:' . $mimeType . ';base64,' . base64_encode((string) file_get_contents($absolutePath));
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

        if (! self::canEmbedInDompdf($mimeType)) {
            $fallbackPath = self::pdfFallbackPath($relativePath);

            if ($fallbackPath === null) {
                return null;
            }

            $absolutePath = $fallbackPath;
            $mimeType = mime_content_type($absolutePath) ?: 'image/svg+xml';
        }

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

        $candidates = self::storageCandidates($path);

        $disk = Storage::disk('public');

        foreach ($candidates as $candidate) {
            if ($disk->exists($candidate)) {
                return $candidate;
            }
        }

        return $path;
    }

    public static function resolvePublicPath(?string $path): ?string
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

    /**
     * @return array<int, string>
     */
    private static function storageCandidates(?string $path): array
    {
        if (blank($path)) {
            return [];
        }

        $path = trim((string) $path);

        if (preg_match('#^https?://#i', $path) === 1) {
            $parsedPath = parse_url($path, PHP_URL_PATH);

            if (is_string($parsedPath) && $parsedPath !== '') {
                $path = ltrim($parsedPath, '/');
            }
        }

        return array_values(array_unique(array_filter([
            $path,
            ltrim($path, '/'),
            str_starts_with($path, 'storage/') ? substr($path, 8) : null,
            str_starts_with($path, 'public/') ? substr($path, 7) : null,
            'acolhidos/avatars/' . basename($path),
            'users/avatars/' . basename($path),
            'avatars/' . basename($path),
            'private/avatars/' . basename($path),
            'private/' . basename($path),
        ])));
    }

    private static function canEmbedInDompdf(string $mimeType): bool
    {
        $mimeType = strtolower(trim($mimeType));

        if (in_array($mimeType, ['image/jpeg', 'image/jpg'], true)) {
            return true;
        }

        if ($mimeType === 'image/png') {
            return function_exists('imagecreatefrompng');
        }

        if ($mimeType === 'image/svg+xml') {
            return true;
        }

        return extension_loaded('gd');
    }

    private static function pdfFallbackPath(string $relativePath): ?string
    {
        $pathInfo = pathinfo($relativePath);
        $directory = $pathInfo['dirname'] ?? '';
        $fileName = $pathInfo['filename'] ?? '';

        if ($fileName === '') {
            return null;
        }

        $fallbackRelativePaths = [
            'images/logo-pdf.svg',
            'grayscale/assets/' . $fileName . '-pdf.svg',
            ltrim(($directory !== '.' ? $directory . '/' : '') . $fileName . '-pdf.svg', '/'),
        ];

        foreach ($fallbackRelativePaths as $fallbackRelativePath) {
            $fallbackAbsolutePath = public_path($fallbackRelativePath);

            if (is_file($fallbackAbsolutePath) && is_readable($fallbackAbsolutePath)) {
                return $fallbackAbsolutePath;
            }
        }

        return null;
    }
}
