<?php

namespace App\Support;

/**
 * Mengubah isian .env media sosial menjadi URL lengkap.
 * Boleh diisi URL penuh (https://instagram.com/akun) ATAU cukup nama akun (@akun / akun).
 */
class Sosial
{
    private const DASAR = [
        'instagram' => 'https://instagram.com/',
        'youtube' => 'https://youtube.com/@',
        'tiktok' => 'https://tiktok.com/@',
        'facebook' => 'https://facebook.com/',
    ];

    public static function url(string $platform, ?string $nilai): ?string
    {
        $nilai = trim((string) $nilai);
        if ($nilai === '') {
            return null;
        }
        if (preg_match('#^https?://#i', $nilai)) {
            return $nilai;
        }

        return (self::DASAR[$platform] ?? 'https://') . rawurlencode(ltrim($nilai, '@'));
    }
}
