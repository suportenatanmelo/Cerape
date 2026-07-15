<?php

namespace App\Support;

class BrowserDetector
{
    /**
     * @return array{browser: string, platform: string, device: string}
     */
    public function detect(?string $userAgent): array
    {
        $userAgent = trim((string) $userAgent);

        if ($userAgent === '') {
            return [
                'browser' => 'Unknown',
                'platform' => 'Unknown',
                'device' => 'Desktop',
            ];
        }

        $browser = $this->browser($userAgent);
        $platform = $this->platform($userAgent);
        $device = $this->device($userAgent);

        return [
            'browser' => $browser,
            'platform' => $platform,
            'device' => $device,
        ];
    }

    private function browser(string $userAgent): string
    {
        return match (true) {
            preg_match('/edg\//i', $userAgent) === 1 => 'Edge',
            preg_match('/chrome\//i', $userAgent) === 1 && preg_match('/opr\//i', $userAgent) === 0 => 'Chrome',
            preg_match('/firefox\//i', $userAgent) === 1 => 'Firefox',
            preg_match('/safari\//i', $userAgent) === 1 && preg_match('/chrome\//i', $userAgent) === 0 => 'Safari',
            preg_match('/opr\//i', $userAgent) === 1 => 'Opera',
            preg_match('/msie|trident/i', $userAgent) === 1 => 'Internet Explorer',
            default => 'Unknown',
        };
    }

    private function platform(string $userAgent): string
    {
        return match (true) {
            preg_match('/android/i', $userAgent) === 1 => 'Android',
            preg_match('/iphone|ipad|ipod/i', $userAgent) === 1 => 'iOS',
            preg_match('/windows nt/i', $userAgent) === 1 => 'Windows',
            preg_match('/mac os x/i', $userAgent) === 1 => 'MacOS',
            preg_match('/linux/i', $userAgent) === 1 => 'Linux',
            default => 'Unknown',
        };
    }

    private function device(string $userAgent): string
    {
        return match (true) {
            preg_match('/tablet|ipad/i', $userAgent) === 1 => 'Tablet',
            preg_match('/mobi|android/i', $userAgent) === 1 => 'Mobile',
            default => 'Desktop',
        };
    }
}
