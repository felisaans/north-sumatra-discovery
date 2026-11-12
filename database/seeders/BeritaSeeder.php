<?php

namespace Database\Seeders;

use App\Models\Berita;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        // [judul, ringkasan, label, tanggal]
        $data = [
            ['Mengenal Arsik, Ikan Mas Berbumbu Andaliman dari Tanah Batak',
                'Hidangan yang sering hadir di pesta adat, dengan bumbu yang dimasak pelan hingga meresap.',
                'Kuliner', '2026-11-05'],
            ['Apa Arti Ulos dalam Pesta Adat Batak?',
                'Kain tenun yang diberikan sebagai tanda kasih dan doa, dengan motif dan warna yang punya makna.',
                'Budaya & Tradisi', '2026-11-04'],
            ['Dari Kesawan Sampai Kedai Kopi: Medan di Waktu Malam',
                'Jalan kaki sore, makan di lorong, lalu duduk lama di kedai kopi.',
                'Cerita Kota', '2026-11-03'],
        ];

        // berita tidak punya slug; judul dipakai sebagai kunci supaya seeder aman diulang
        foreach ($data as [$judul, $ringkasan, $label, $tanggal]) {
            Berita::updateOrCreate(['judul' => $judul], [
                'ringkasan' => $ringkasan,
                'label' => $label,
                'tanggal' => $tanggal,
            ]);
        }
    }
}
