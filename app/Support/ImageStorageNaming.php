<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

final class ImageStorageNaming
{
    public static function directory(string $category): string
    {
        return 'imagens/' . trim($category, '/');
    }

    public static function datedDirectory(string $category): string
    {
        return self::directory($category) . '/' . now()->format('Y/m/d');
    }

    public static function filename(
        TemporaryUploadedFile $file,
        string $category,
        ?string $label = null,
        ?string $identifier = null,
    ): string {
        $label = self::shortLabel($label ?: pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));

        $segments = array_filter([
            now()->format('H-i-s'),
            self::slug($category),
            self::slug($identifier ?: 'novo'),
            $label,
        ]);

        return implode('-', $segments) . '.' . $file->getClientOriginalExtension();
    }

    public static function canonicalFilename(
        string $category,
        string|int $identifier,
        ?string $label = null,
        ?string $extension = null,
    ): string {
        $label = self::shortLabel($label ?? 'imagem');

        $segments = array_filter([
            now()->format('H-i-s'),
            self::slug($category),
            self::slug((string) $identifier),
            $label,
        ]);

        return implode('-', $segments) . '.' . self::sanitizeExtension($extension);
    }

    public static function canonicalPath(
        string $category,
        string|int $identifier,
        ?string $label = null,
        ?string $extension = null,
    ): string {
        return self::datedDirectory($category) . '/' . self::canonicalFilename($category, $identifier, $label, $extension);
    }

    public static function syncStoredImage(
        Model $model,
        string $attribute,
        string $category,
        ?string $label = null,
    ): void {
        if (! $model->exists) {
            return;
        }

        $currentPath = self::normalizePath($model->getAttribute($attribute));

        if ($currentPath === null) {
            return;
        }

        if (Str::startsWith($currentPath, ['http://', 'https://', '//', 'data:'])) {
            return;
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($currentPath)) {
            return;
        }

        $extension = pathinfo($currentPath, PATHINFO_EXTENSION) ?: 'jpg';
        $finalPath = self::canonicalPath($category, (string) $model->getKey(), $label, $extension);

        if ($currentPath === $finalPath) {
            return;
        }

        $disk->makeDirectory(dirname($finalPath));

        if ($disk->exists($finalPath)) {
            $disk->delete($finalPath);
        }

        $disk->move($currentPath, $finalPath);

        $model->forceFill([
            $attribute => $finalPath,
        ])->saveQuietly();
    }

    private static function slug(string $value): string
    {
        $value = Str::of($value)
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '-')
            ->trim('-')
            ->value();

        return $value !== '' ? $value : 'imagem';
    }

    private static function shortLabel(string $value, int $max = 48): string
    {
        $value = self::slug($value);

        return Str::limit($value, $max, '');
    }

    private static function normalizePath(mixed $path): ?string
    {
        if (! is_string($path)) {
            return null;
        }

        $path = trim($path);

        if ($path === '') {
            return null;
        }

        return ltrim(Str::replaceFirst('storage/', '', $path), '/');
    }

    private static function sanitizeExtension(?string $extension): string
    {
        $extension = strtolower(trim((string) $extension));

        return $extension !== '' ? preg_replace('/[^a-z0-9]+/', '', $extension) ?: 'jpg' : 'jpg';
    }
}
