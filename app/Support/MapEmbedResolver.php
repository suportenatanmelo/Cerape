<?php

namespace App\Support;

use Illuminate\Support\Str;

final class MapEmbedResolver
{
    public static function src(mixed $value, ?string $fallbackAddress = null): ?string
    {
        if (is_string($value)) {
            $value = trim($value);

            if ($value !== '') {
                if (preg_match('/src\s*=\s*["\']([^"\']+)["\']/i', $value, $matches)) {
                    return $matches[1];
                }

                if (Str::contains($value, ['google.com/maps', 'maps.google', 'google.maps'])) {
                    return $value;
                }

                if (Str::startsWith($value, ['http://', 'https://', '//'])) {
                    return $value;
                }
            }
        }

        if (filled($fallbackAddress)) {
            return 'https://www.google.com/maps?q=' . urlencode($fallbackAddress) . '&output=embed';
        }

        return null;
    }

    public static function searchUrl(string $address): string
    {
        return 'https://www.google.com/maps/search/?api=1&query=' . urlencode($address);
    }
}
