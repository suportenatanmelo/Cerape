<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

final class ImageStorageNaming
{
    public const ROOT_DIRECTORY = 'documentos';

    public static function directory(string $category): string
    {
        return self::ROOT_DIRECTORY . '/' . self::slug(trim($category, '/'));
    }

    public static function filename(
        TemporaryUploadedFile $file,
        string $category,
        ?string $label = null,
        ?string $identifier = null,
    ): string {
        $label = self::shortLabel($label ?: pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));

        $segments = array_filter([
            self::slug($identifier ?: 'sem-id'),
            self::slug($category),
            now()->format('Y-m-d-H-i-s'),
        ]);

        return implode('-', $segments) . '.' . self::sanitizeExtension($file->getClientOriginalExtension());
    }

    public static function canonicalFilename(
        string $category,
        string|int $identifier,
        ?string $label = null,
        ?string $extension = null,
    ): string {
        $label = self::shortLabel($label ?? 'imagem');

        $segments = array_filter([
            self::slug((string) $identifier),
            self::slug($category),
            now()->format('Y-m-d-H-i-s'),
        ]);

        return implode('-', $segments) . '.' . self::sanitizeExtension($extension);
    }

    public static function canonicalPath(
        string $category,
        string|int $identifier,
        ?string $label = null,
        ?string $extension = null,
    ): string {
        return self::directory($category) . '/' . self::canonicalFilename($category, $identifier, $label, $extension);
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
        $finalPath = self::canonicalPath($category, (string) $model->getKey(), self::modelName($model, $label), $extension);

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

    public static function syncStoredFile(
        Model $model,
        string $attribute,
        string $category,
        ?string $label = null,
    ): void {
        $currentValue = $model->getAttribute($attribute);

        if (is_array($currentValue)) {
            self::syncStoredFileArray($model, $attribute, $category, $label, $currentValue);

            return;
        }

        self::syncStoredImage($model, $attribute, $category, $label);
    }

    public static function removeStoredPath(?string $path): void
    {
        $currentPath = self::normalizePath($path);

        if ($currentPath === null || Str::startsWith($currentPath, ['http://', 'https://', '//', 'data:'])) {
            return;
        }

        $disk = Storage::disk('public');

        if ($disk->exists($currentPath)) {
            $disk->delete($currentPath);
        }
    }

    /**
     * @param array<int, mixed> $paths
     */
    public static function removeStoredPaths(array $paths): void
    {
        foreach ($paths as $path) {
            self::removeStoredPath(is_string($path) ? $path : null);
        }
    }

    /**
     * @param array<int, mixed> $paths
     */
    private static function syncStoredFileArray(
        Model $model,
        string $attribute,
        string $category,
        ?string $label,
        array $paths,
    ): void {
        if (! $model->exists) {
            return;
        }

        $disk = Storage::disk('public');
        $updated = [];

        foreach (array_values($paths) as $index => $path) {
            $currentPath = self::normalizePath($path);

            if ($currentPath === null || Str::startsWith($currentPath, ['http://', 'https://', '//', 'data:'])) {
                $updated[] = $path;

                continue;
            }

            if (! $disk->exists($currentPath)) {
                $updated[] = $currentPath;

                continue;
            }

            $extension = pathinfo($currentPath, PATHINFO_EXTENSION) ?: 'jpg';
            $itemLabel = trim((string) $label . '-' . ($index + 1), '-');
            $finalPath = self::canonicalPath($category, (string) $model->getKey(), $itemLabel, $extension);

            if ($currentPath !== $finalPath) {
                $disk->makeDirectory(dirname($finalPath));

                if ($disk->exists($finalPath)) {
                    $disk->delete($finalPath);
                }

                $disk->move($currentPath, $finalPath);
            }

            $updated[] = $finalPath;
        }

        $model->forceFill([
            $attribute => $updated,
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

    private static function modelName(Model $model, ?string $label = null): string
    {
        return self::shortLabel($label ?: class_basename($model));
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
