<?php

namespace App\Helpers;

/**
 * Parser user-agent ringan tanpa dependency:
 * mengekstrak OS, browser (+versi), dan tipe perangkat.
 */
class UserAgentParser
{
    public static function parse(?string $ua): array
    {
        $ua = (string) $ua;

        return [
            'os' => self::os($ua),
            'browser' => self::browser($ua),
            'device' => self::device($ua),
        ];
    }

    public static function deviceIcon(string $device): string
    {
        return match ($device) {
            'HP' => 'smartphone',
            'Tablet' => 'tablet',
            default => 'monitor',
        };
    }

    private static function os(string $ua): string
    {
        if (preg_match('/Windows NT 10\.0/', $ua)) return 'Windows 10 / 11';
        if (preg_match('/Windows NT 6\.3/', $ua)) return 'Windows 8.1';
        if (preg_match('/Windows NT 6\.1/', $ua)) return 'Windows 7';
        if (preg_match('/Windows/', $ua)) return 'Windows';
        if (preg_match('/Android (\d+(?:\.\d+)?)/', $ua, $m)) return 'Android ' . $m[1];
        if (preg_match('/Android/', $ua)) return 'Android';
        if (preg_match('/iPhone OS (\d+)_(\d+)/', $ua, $m)) return 'iOS ' . $m[1] . '.' . $m[2];
        if (preg_match('/iPhone|iPad|iOS/', $ua)) return 'iOS';
        if (preg_match('/Mac OS X (\d+)[_.](\d+)/', $ua, $m)) return 'macOS ' . $m[1] . '.' . $m[2];
        if (preg_match('/Mac OS|Macintosh/', $ua)) return 'macOS';
        if (preg_match('/Ubuntu/', $ua)) return 'Ubuntu';
        if (preg_match('/CrOS/', $ua)) return 'ChromeOS';
        if (preg_match('/Linux/', $ua)) return 'Linux';

        return 'Tidak dikenal';
    }

    private static function browser(string $ua): string
    {
        if (preg_match('/Edg\/(\d+)/', $ua, $m)) return 'Edge ' . $m[1];
        if (preg_match('/OPR\/(\d+)/', $ua, $m)) return 'Opera ' . $m[1];
        if (preg_match('/SamsungBrowser\/(\d+)/', $ua, $m)) return 'Samsung Internet ' . $m[1];
        if (preg_match('/Chrome\/(\d+)/', $ua, $m)) return 'Chrome ' . $m[1];
        if (preg_match('/Firefox\/(\d+)/', $ua, $m)) return 'Firefox ' . $m[1];
        if (preg_match('/Version\/(\d+).*Safari/', $ua, $m)) return 'Safari ' . $m[1];
        if (preg_match('/Safari/', $ua)) return 'Safari';
        if (preg_match('/MSIE (\d+)/', $ua, $m)) return 'IE ' . $m[1];

        return 'Tidak dikenal';
    }

    private static function device(string $ua): string
    {
        if (preg_match('/Tablet|iPad/', $ua)) return 'Tablet';
        if (preg_match('/Mobile|Android|iPhone|IEMobile/', $ua)) return 'HP';

        return 'Desktop';
    }
}
