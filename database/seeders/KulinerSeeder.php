<?php

namespace Database\Seeders;

use App\Models\Daerah;
use App\Models\Kategori;
use App\Models\Kuliner;
use Illuminate\Database\Seeder;

class KulinerSeeder extends Seeder
{
    public function run(): void
    {
        // [nama, slug, deskripsi, bahan_utama, harga_min, harga_max, gambar, slug_daerah, slug_kategori]
        $data = [
            ['Soto Medan', 'soto-medan', 'Soto berkuah santan yang gurih, berisi daging atau ayam, dengan kentang goreng, perasan jeruk nipis, dan sambal di sisi piring.', 'Santan, daging atau ayam, kentang goreng', 20000, 40000, 'https://placehold.co/600x600/F8E8D0/54205F?text=Soto+Medan', 'kota-medan', 'makanan-berat'],
            ['Mie Gomak', 'mie-gomak', 'Mi tebal mirip spageti dengan bumbu rempah dan andaliman, disajikan berkuah atau digoreng.', 'Mi tebal, andaliman, rempah', 20000, 35000, 'https://placehold.co/600x600/F8E8D0/54205F?text=Mie+Gomak', 'toba', 'makanan-berat'],
            ['Arsik Ikan Mas', 'arsik-ikan-mas', 'Ikan mas berbumbu andaliman, kunyit, dan asam yang dimasak pelan. Hidangan yang sering hadir di pesta adat Batak.', 'Ikan mas, andaliman, kunyit', 40000, 80000, 'https://placehold.co/600x600/F8E8D0/54205F?text=Arsik+Ikan+Mas', 'toba', 'makanan-berat'],
            ['Saksang', 'saksang', 'Masakan daging berbumbu andaliman dan santan khas Batak. Tersedia dalam beberapa versi, jadi tanyakan bahan yang dipakai sebelum memesan.', 'Daging, andaliman, santan', 25000, 50000, 'https://placehold.co/600x600/F8E8D0/54205F?text=Saksang', 'tapanuli-utara', 'makanan-berat'],
            ['Lontong Medan', 'lontong-medan', 'Lontong dengan kuah santan, sayur, telur, dan serundeng. Sarapan yang mengenyangkan di Medan.', 'Lontong, santan, sayur, telur', 15000, 30000, 'https://placehold.co/600x600/F8E8D0/54205F?text=Lontong+Medan', 'kota-medan', 'makanan-berat'],
            ['Kopi Sidikalang', 'kopi-sidikalang', 'Kopi dari dataran tinggi Dairi yang dikenal beraroma kuat, biasa disajikan di kedai kopi sepanjang hari.', 'Biji kopi, gula', 10000, 25000, 'https://placehold.co/600x600/F8E8D0/54205F?text=Kopi+Sidikalang', 'dairi', 'minuman'],
            ['Dali ni Horbo', 'dali-ni-horbo', 'Olahan susu kerbau tradisional Batak yang digumpalkan, bertekstur lembut seperti keju segar.', 'Susu kerbau', 15000, 35000, 'https://placehold.co/600x600/F8E8D0/54205F?text=Dali+ni+Horbo', 'tapanuli-utara', 'kue-tradisional'],
            ['Bika Ambon', 'bika-ambon', 'Kue berongga seperti sarang lebah, beraroma santan dan pandan. Oleh-oleh yang paling dikenal dari Medan.', 'Tepung, santan, telur, pandan', 35000, 90000, 'https://placehold.co/600x600/F8E8D0/54205F?text=Bika+Ambon', 'kota-medan', 'kue-tradisional'],
            ['Bolu Gulung Medan', 'bolu-gulung-medan', 'Bolu gulung lembut dengan isian beragam rasa, juga banyak dibawa pulang dari Medan.', 'Tepung, telur, mentega', 30000, 80000, 'https://placehold.co/600x600/F8E8D0/54205F?text=Bolu+Gulung+Medan', 'kota-medan', 'kue-tradisional'],
        ];

        // Rekomendasi spot kuliner (maks 3 per kuliner): slug => [[nama tempat, link Google Maps], ...]
        // Hanya slug yang diisi di sini yang diperbarui, jadi isian admin untuk slug lain tidak tertimpa.
        // Contoh: 'arsik-ikan-mas' => [['Nama Rumah Makan', 'https://maps.app.goo.gl/xxxx']],
        $spot = [];

        foreach ($data as [$nama, $slug, $deskripsi, $bahan, $min, $max, $gambar, $daerah, $kategori]) {
            $spotAttr = [];
            foreach (array_slice($spot[$slug] ?? [], 0, 3) as $i => [$namaSpot, $urlSpot]) {
                $spotAttr['spot_'.($i + 1).'_nama'] = $namaSpot;
                $spotAttr['spot_'.($i + 1).'_url'] = $urlSpot;
            }

            Kuliner::updateOrCreate(['slug' => $slug], [
                'nama' => $nama,
                'deskripsi' => $deskripsi,
                'bahan_utama' => $bahan,
                'harga_min' => $min,
                'harga_max' => $max,
                'gambar' => $gambar,
                // cari id lewat slug (value('id') = ambil 1 kolom saja)
                'daerah_id' => Daerah::where('slug', $daerah)->value('id'),
                'kategori_id' => Kategori::where('tipe', Kategori::TIPE_KULINER)->where('slug', $kategori)->value('id'),
            ] + $spotAttr);
        }
    }
}
