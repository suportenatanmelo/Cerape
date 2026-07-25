<?php

namespace App\Support;

class DocumentBranding
{
    /**
     * @return array{brandName: string, letterhead: string|null, logo: string|null, generatedAt: string}
     */
    public static function data(): array
    {
        $logo = null;

        try {
            $brandName = SystemBranding::brandName('CERAPE');
            $logoPath = public_path(SystemBranding::logoPublicPath(''));

            if (is_file($logoPath)) {
                $mime = mime_content_type($logoPath) ?: 'image/png';
                $logo = 'data:' . $mime . ';base64,' . base64_encode((string) file_get_contents($logoPath));
            }
        } catch (\Throwable) {
            // PDF rendering must remain available when an optional settings store is unavailable.
            $brandName = 'CERAPE';
        }

        return [
            'brandName' => $brandName,
            'letterhead' => self::letterheadDataUri(),
            'logo' => $logo,
            'generatedAt' => now()->format('d/m/Y H:i'),
        ];
    }

    private static function letterheadDataUri(): ?string
    {
        $path = public_path('assets/cerape-papel-timbrado.jpg');

        if (! is_file($path)) {
            return null;
        }

        return 'data:image/jpeg;base64,' . base64_encode((string) file_get_contents($path));
    }
}
