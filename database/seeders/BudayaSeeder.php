<?php

namespace Database\Seeders;

use App\Models\Budaya;
use App\Models\Daerah;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class BudayaSeeder extends Seeder
{
    public function run(): void
    {
        // [nama, slug, deskripsi, gambar, sorotan, slug_daerah, slug_kategori]
        $data = [
            ['Tor-tor', 'tor-tor', 'Tarian Batak yang dibawakan mengikuti irama gondang. Gerak tangan dan kakinya menyampaikan doa, syukur, dan penghormatan dalam pesta adat.', 'https://placehold.co/400x400/F8E8D0/54205F?text=Tor-tor', 'Tarian upacara adat', 'samosir', 'tari'],
            ['Gordang Sambilan', 'gordang-sambilan', 'Ansambel sembilan gendang besar dari tanah Mandailing yang dimainkan dalam upacara adat.', 'https://placehold.co/400x400/F8E8D0/54205F?text=Gordang+Sambilan', 'Sembilan gendang', 'mandailing-natal', 'musik'],
            ['Rumah Bolon', 'rumah-bolon', 'Rumah adat Batak Toba berbentuk panggung dengan atap melengkung dan ukiran gorga di dindingnya.', 'https://placehold.co/400x400/F8E8D0/54205F?text=Rumah+Bolon', 'Atap melengkung dan gorga', 'samosir', 'arsitektur'],
            ['Ulos', 'ulos', 'Kain tenun Batak yang dipakai dan diberikan dalam banyak upacara adat. Setiap jenis dan motifnya punya makna.', 'https://placehold.co/400x400/F8E8D0/54205F?text=Ulos', 'Kain penuh makna', 'toba', 'kerajinan'],
            ['Mangulosi', 'mangulosi', 'Prosesi memberikan ulos kepada keluarga dalam pesta adat sebagai tanda kasih dan doa.', 'https://placehold.co/400x400/F8E8D0/54205F?text=Mangulosi', 'Tradisi memberi ulos', 'toba', 'upacara'],
            ['Sigale-gale', 'sigale-gale', 'Patung kayu yang digerakkan dengan tali dan menari mengikuti gondang, dikenal di Samosir.', 'https://placehold.co/400x400/F8E8D0/54205F?text=Sigale-gale', 'Patung penari', 'samosir', 'tari'],
            ['Hombo Batu (Lompat Batu)', 'lompat-batu', 'Tradisi melompati susunan batu setinggi sekitar dua meter di Nias Selatan.', 'https://placehold.co/400x400/F8E8D0/54205F?text=Hombo+Batu+(Lompat+Batu)', 'Tradisi Nias Selatan', 'nias-selatan', 'upacara'],
            ['Tari Serampang Dua Belas', 'serampang-dua-belas', 'Tarian pergaulan Melayu pesisir timur dengan irama yang riang dan gerak yang bergantian antara penari.', 'https://placehold.co/400x400/F8E8D0/54205F?text=Tari+Serampang+Dua+Belas', 'Budaya Melayu pesisir', 'deli-serdang', 'tari'],
        ];

        // Link video budaya (tombol "Tonton ..."): slug => URL video. Hanya slug yang diisi yang diperbarui.
        // Contoh: 'tor-tor' => 'https://www.youtube.com/watch?v=xxxxxxxxxxx',
        $video = [];

        foreach ($data as [$nama, $slug, $deskripsi, $gambar, $sorotan, $daerah, $kategori]) {
            $videoAttr = isset($video[$slug]) ? ['video_url' => $video[$slug]] : [];

            Budaya::updateOrCreate(['slug' => $slug], [
                'nama' => $nama,
                'deskripsi' => $deskripsi,
                'gambar' => $gambar,
                'sorotan' => $sorotan,
                'daerah_id' => Daerah::where('slug', $daerah)->value('id'),
                'kategori_id' => Kategori::where('tipe', Kategori::TIPE_BUDAYA)->where('slug', $kategori)->value('id'),
            ] + $videoAttr);
        }
    }
}
