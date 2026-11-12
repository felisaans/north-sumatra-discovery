<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

/**
 * Trait kecil untuk mengubah nilai kolom gambar menjadi URL siap pakai.
 * Setara fungsi imgUrl() di api.js lama:
 *  - http(s)://...  -> dipakai apa adanya (gambar editorial = URL eksternal)
 *  - assets/...     -> aset lokal di folder public/ (lewat asset())
 *  - selain itu     -> dianggap path di disk 'public' (mis. foto pengalaman)
 */
trait PunyaUrlGambar
{
    protected function urlDari(?string $nilai): ?string
    {
        if ($nilai === null || $nilai === '') {
            return null;
        }

        if (preg_match('#^https?://#i', $nilai)) {
            return $nilai;
        }

        if (str_starts_with($nilai, 'assets/')) {
            return asset($nilai);
        }

        return Storage::disk('public')->url($nilai);
    }
}
