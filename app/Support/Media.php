<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Pembantu URL media: nilai yang sudah berupa URL penuh (http/https/ //) dipakai apa adanya
 * (file di-host online: CDN / R2 / Bunny / dsb), selain itu dianggap path di disk 'public'.
 * Nilai kosong -> null.
 */
class Media
{
    public static function url(?string $nilai): ?string
    {
        $nilai = trim((string) $nilai);
        if ($nilai === '') {
            return null;
        }
        if (preg_match('#^(https?:)?//#i', $nilai)) {
            return $nilai;
        }

        return Storage::disk('public')->url($nilai);
    }

    /**
     * Ambil ID video YouTube (11 karakter) dari ID polos atau URL: watch?v=, youtu.be/, shorts/, embed/, live/.
     * Mengembalikan null bila kosong / tidak dikenali.
     */
    public static function youtubeId(?string $mentah): ?string
    {
        $mentah = trim((string) $mentah);
        if ($mentah === '') {
            return null;
        }
        if (preg_match('~^[A-Za-z0-9_-]{11}$~', $mentah)) {
            return $mentah;
        }
        if (preg_match('~(?:youtu\.be/|youtube(?:-nocookie)?\.com/(?:watch\?(?:[^#]*&)?v=|embed/|shorts/|live/))([A-Za-z0-9_-]{11})~', $mentah, $m)) {
            return $m[1];
        }
        return null;
    }

    /**
     * Ambil ID video TikTok (15-20 digit) dari ID polos atau URL: tiktok.com/@akun/video/ID, /embed/v2/ID, /player/v1/ID.
     * Link pendek (vm.tiktok.com / vt.tiktok.com) tidak bisa dibaca tanpa request jaringan -> null.
     */
    public static function tiktokId(?string $mentah): ?string
    {
        $mentah = trim((string) $mentah);
        if ($mentah === '') {
            return null;
        }
        if (preg_match('~^\d{15,20}$~', $mentah)) {
            return $mentah;
        }
        if (preg_match('~tiktok\.com/(?:@[^/]+/video|embed(?:/v2)?|player/v1)/(\d{15,20})~i', $mentah, $m)) {
            return $m[1];
        }
        return null;
    }
}
