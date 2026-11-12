<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Menyimpan foto unggahan user dalam bentuk RINGAN: sisi terpanjang dibatasi (default 1600 px),
 * disimpan sebagai WebP (kualitas 80), orientasi EXIF dibetulkan, metadata (mis. lokasi GPS) ikut terbuang.
 * Foto 3-5 MB dari HP -> ± 100-250 KB.
 *
 * Memakai ekstensi GD bawaan PHP (tanpa paket tambahan). Jika GD / WebP tidak tersedia,
 * atau file gagal diproses, file asli disimpan apa adanya (unggahan user tidak pernah gagal karena ini).
 */
class FotoUpload
{
    public const SISI_MAKS = 1600;   // GANTI DI SINI: piksel sisi terpanjang
    public const KUALITAS = 80;      // GANTI DI SINI: kualitas WebP 0-100

    /** @return string path relatif di disk, mis. "pengalaman/ab12cd.webp" */
    public static function simpan(UploadedFile $file, string $folder = 'pengalaman', string $disk = 'public'): string
    {
        $biner = self::perkecil($file);

        if ($biner === null) {
            return $file->store($folder, $disk);   // cadangan: simpan file asli
        }

        $path = trim($folder, '/').'/'.Str::random(40).'.webp';
        Storage::disk($disk)->put($path, $biner);

        return $path;
    }

    private static function perkecil(UploadedFile $file): ?string
    {
        if (! function_exists('imagecreatefromstring') || ! function_exists('imagewebp')) {
            return null;
        }

        $isi = @file_get_contents($file->getRealPath());
        $img = $isi === false ? false : @imagecreatefromstring($isi);
        if (! $img) {
            return null;
        }

        // Putar sesuai EXIF (foto HP sering "miring" di data, bukan di piksel) — hanya untuk JPEG
        if (function_exists('exif_read_data') && in_array($file->getMimeType(), ['image/jpeg', 'image/jpg'], true)) {
            $exif = @exif_read_data($file->getRealPath());
            $sudut = match ($exif['Orientation'] ?? 1) {
                3 => 180, 6 => -90, 8 => 90, default => 0,
            };
            if ($sudut !== 0 && ($putar = imagerotate($img, $sudut, 0))) {
                $img = $putar;
            }
        }

        $w = imagesx($img);
        $h = imagesy($img);
        $skala = min(1, self::SISI_MAKS / max($w, $h));   // tidak pernah memperbesar
        if ($skala < 1) {
            $baru = imagescale($img, (int) round($w * $skala), (int) round($h * $skala), IMG_BICUBIC);
            if ($baru) {
                $img = $baru;
            }
        }

        imagepalettetotruecolor($img);
        imagealphablending($img, false);
        imagesavealpha($img, true);   // PNG transparan tetap transparan

        ob_start();
        $ok = imagewebp($img, null, self::KUALITAS);
        $hasil = ob_get_clean();

        return $ok && $hasil !== '' ? $hasil : null;
    }
}
