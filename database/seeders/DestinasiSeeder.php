<?php

namespace Database\Seeders;

use App\Models\Daerah;
use App\Models\Destinasi;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class DestinasiSeeder extends Seeder
{
    public function run(): void
    {
        // [nama, slug, deskripsi, gambar, jenis_wisata, slug_daerah, slug_kategori]
        $data = [
            ['Danau Toba', 'danau-toba', 'Danau vulkanik terbesar di dunia, dengan Pulau Samosir dan desa-desa adat di sekelilingnya.', 'https://placehold.co/600x400/F8E8D0/54205F?text=Danau+Toba', 'Wisata Alam', 'toba', 'alam'],
            ['Istana Maimun', 'istana-maimun', 'Istana Kesultanan Deli di Medan dengan arsitektur perpaduan Melayu, Timur Tengah, dan Eropa.', 'https://placehold.co/600x400/F8E8D0/54205F?text=Istana+Maimun', 'Wisata Sejarah & Edukasi', 'kota-medan', 'sejarah-edukasi'],
            ['Masjid Raya Al-Mashun', 'masjid-raya-al-mashun', 'Masjid bersejarah di pusat Medan, bersebelahan dengan Istana Maimun.', 'https://placehold.co/600x400/F8E8D0/54205F?text=Masjid+Raya+Al-Mashun', 'Wisata Sejarah & Religi', 'kota-medan', 'sejarah-religi'],
            ['Berastagi', 'berastagi', 'Kota dataran tinggi Karo yang sejuk, dengan pasar buah dan sayur serta Gunung Sibayak di latarnya.', 'https://placehold.co/600x400/F8E8D0/54205F?text=Berastagi', 'Wisata Alam', 'karo', 'alam'],
            ['Bukit Lawang', 'bukit-lawang', 'Hutan hujan di tepi sungai, tempat melihat orangutan di habitat aslinya.', 'https://placehold.co/600x400/F8E8D0/54205F?text=Bukit+Lawang', 'Wisata Alam', 'langkat', 'alam'],
            ['Desa Bawomataluo', 'desa-bawomataluo', 'Desa adat berundak di Nias Selatan dengan rumah kayu besar dan tradisi lompat batu.', 'https://placehold.co/600x400/F8E8D0/54205F?text=Desa+Bawomataluo', 'Wisata Sejarah & Edukasi', 'nias-selatan', 'sejarah-edukasi'],
        ];

        // Link Google Maps destinasi: slug => URL. Hanya slug yang diisi yang diperbarui.
        // Contoh: 'danau-toba' => 'https://maps.app.goo.gl/xxxx',
        $maps = [];

        foreach ($data as [$nama, $slug, $deskripsi, $gambar, $jenis, $daerah, $kategori]) {
            $mapsAttr = isset($maps[$slug]) ? ['maps_url' => $maps[$slug]] : [];

            Destinasi::updateOrCreate(['slug' => $slug], [
                'nama' => $nama,
                'deskripsi' => $deskripsi,
                'gambar' => $gambar,
                'jenis_wisata' => $jenis,
                'daerah_id' => Daerah::where('slug', $daerah)->value('id'),
                'kategori_id' => Kategori::where('tipe', Kategori::TIPE_DESTINASI)->where('slug', $kategori)->value('id'),
            ] + $mapsAttr);
        }
    }
}
