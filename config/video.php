<?php

/*
 | Media beranda (hero + Sorotan Editorial).
 |
 | HERO      : video di-host ONLINE (URL penuh), diisi lewat .env -> HERO_VIDEO_URL (+ HERO_POSTER_URL opsional).
 |             Tidak ada file video di project / storage. Nilai tanpa http(s):// dianggap path di disk 'public'.
 | EDITORIAL : 4 video (tor-tor 16:9; arsik, danau toba, bika ambon 9:16) juga di-host online (.env -> EDITORIAL_<NAMA>_VIDEO_URL). Gambar 'latar' dipakai sebagai
 |             latar kartu DAN sebagai poster/cadangan: tampil selagi video dimuat, atau terus tampil bila
 |             video gagal dimuat (sinyal lemah, hemat data, URL salah) atau URL video belum diisi.
 |
 | lebar/tinggi = rasio frame (piksel). Dipakai CSS (aspect-ratio) supaya frame tidak melompat saat memuat.
 */
return [
    'hero' => [
        // GANTI DI .env: HERO_VIDEO_URL=https://cdn.contoh.com/hero-beranda.mp4
        'video'  => env('HERO_VIDEO_URL'),
        'poster' => env('HERO_POSTER_URL'),   // opsional: gambar pratinjau (.webp/.jpg) selagi video dimuat
        'lebar'  => 720,
        'tinggi' => 1280,   // 9:16 portrait (ditampilkan di frame 4:5 dengan object-fit: cover)
    ],

    'editorial' => [
        'tor-tor' => [
            'video'  => (env('EDITORIAL_TOR_TOR_VIDEO_URL') ?: 'https://res.cloudinary.com/ojm5bajr/video/upload/v1794293702/Tari_Kreasi_Nusantara_-_Hari_Tari_Sedunia_2026_27_44.mp4'),
            'youtube' => env('EDITORIAL_TOR_TOR_YOUTUBE'),          // OPSIONAL: link/ID YouTube (jika diisi, dipakai lebih dulu daripada 'video'). Senyap & berulang
            'mulai'   => (int) env('EDITORIAL_TOR_TOR_MULAI', 0),    // detik awal potongan
            'selesai' => (int) env('EDITORIAL_TOR_TOR_SELESAI', 30), // detik akhir potongan   // poster otomatis = gambar 'latar' di bawah
            'lebar'  => 1920,
            'tinggi' => 1080,   // 16:9 landscape
            // Gambar kartu: latar blur kartu + poster/cadangan video. Hotlink online.
            // tema 'gelap' = gambar gelap -> teks cerah | 'terang' = gambar terang -> teks gelap.  GANTI DI SINI bila perlu.
            'latar'  => ['url' => (env('EDITORIAL_TOR_TOR_LATAR') ?: 'https://static.promediateknologi.id/crop/0x0:0x0/1200x0/webp/photo/koropakcoid/2024/11/KOROPAK.CO_.ID-Mengungkap-Cinta-Lewat-Tari-Tortor-Hata-Sopisik.jpg'), 'tema' => 'gelap'],
        ],
        'arsik-ikan-mas' => [
            'video'  => (env('EDITORIAL_ARSIK_IKAN_MAS_VIDEO_URL') ?: 'https://res.cloudinary.com/ojm5bajr/video/upload/v1794293725/tex_ee_video_no_watermark.mp4'),
            'youtube' => env('EDITORIAL_ARSIK_IKAN_MAS_YOUTUBE'),          // OPSIONAL: link/ID YouTube (jika diisi, dipakai lebih dulu daripada 'video'). Senyap & berulang
            'mulai'   => (int) env('EDITORIAL_ARSIK_IKAN_MAS_MULAI', 0),    // detik awal potongan
            'selesai' => (int) env('EDITORIAL_ARSIK_IKAN_MAS_SELESAI', 30), // detik akhir potongan   // poster otomatis = gambar 'latar' di bawah
            'lebar'  => 1080,
            'tinggi' => 1920,   // 9:16 portrait
            // Gambar kartu: latar blur kartu + poster/cadangan video. Hotlink online.
            // tema 'gelap' = gambar gelap -> teks cerah | 'terang' = gambar terang -> teks gelap.  GANTI DI SINI bila perlu.
            'latar'  => ['url' => (env('EDITORIAL_ARSIK_IKAN_MAS_LATAR') ?: 'https://buckets.sasa.co.id/v1/AUTH_Assets/Assets/p/website/medias/page_medias/resep_arsik_ikan_mas.jpg'), 'tema' => 'gelap'],
        ],
        'danau-toba' => [
            'video'  => (env('EDITORIAL_DANAU_TOBA_VIDEO_URL') ?: 'https://res.cloudinary.com/ojm5bajr/video/upload/v1794293749/Wonderful_Indonesia_video_no_watermark.mp4'),
            'youtube' => env('EDITORIAL_DANAU_TOBA_YOUTUBE'),          // OPSIONAL: link/ID YouTube (jika diisi, dipakai lebih dulu daripada 'video'). Senyap & berulang
            'mulai'   => (int) env('EDITORIAL_DANAU_TOBA_MULAI', 0),    // detik awal potongan
            'selesai' => (int) env('EDITORIAL_DANAU_TOBA_SELESAI', 30), // detik akhir potongan   // poster otomatis = gambar 'latar' di bawah
            'lebar'  => 1080,
            'tinggi' => 1920,   // 9:16 portrait
            // Gambar kartu: latar blur kartu + poster/cadangan video. Hotlink online.
            // tema 'gelap' = gambar gelap -> teks cerah | 'terang' = gambar terang -> teks gelap.  GANTI DI SINI bila perlu.
            'latar'  => ['url' => (env('EDITORIAL_DANAU_TOBA_LATAR') ?: 'https://asset.kompas.com/crops/rHgAetzNW96MPPmm3Y56xMN4zH4=/0x0:1080x720/1200x800/data/photo/2021/01/29/6013ebcbbe089.jpg'), 'tema' => 'gelap'],
        ],
        'bika-ambon' => [
            'video'  => (env('EDITORIAL_BIKA_AMBON_VIDEO_URL') ?: 'https://res.cloudinary.com/ojm5bajr/video/upload/v1794293778/maknaceritera_video_no_watermark.mp4'),
            'youtube' => env('EDITORIAL_BIKA_AMBON_YOUTUBE'),          // OPSIONAL: link/ID YouTube (jika diisi, dipakai lebih dulu daripada 'video'). Senyap & berulang
            'mulai'   => (int) env('EDITORIAL_BIKA_AMBON_MULAI', 0),    // detik awal potongan
            'selesai' => (int) env('EDITORIAL_BIKA_AMBON_SELESAI', 30), // detik akhir potongan   // poster otomatis = gambar 'latar' di bawah
            'lebar'  => 1080,
            'tinggi' => 1920,   // 9:16 portrait
            // Gambar kartu: latar blur kartu + poster/cadangan video. Hotlink online.
            // tema 'gelap' = gambar gelap -> teks cerah | 'terang' = gambar terang -> teks gelap.  GANTI DI SINI bila perlu.
            'latar'  => ['url' => (env('EDITORIAL_BIKA_AMBON_LATAR') ?: 'https://buckets.sasa.co.id/v1/AUTH_Assets/Assets/p/website/medias/page_medias/Bika_Ambon.jpg'), 'tema' => 'gelap'],
        ],
    ],

    /*
     | Foto slider "dua sisi" di hero beranda (geser kota <-> kampung). Hotlink online; kosong / gagal dimuat = ilustrasi SVG bawaan.
     | Dipakai bila HERO_VIDEO_URL / HERO_POSTER_URL kosong (kalau diisi, hero menampilkan video/poster itu sebagai ganti slider).
     */
    'dua_sisi' => [
        'kota'    => env('DUA_SISI_KOTA_URL')    ?: 'https://res.cloudinary.com/ojm5bajr/image/upload/v1794296243/pexels-murod-lens-1082996343-38178385.jpg',
        'kampung' => env('DUA_SISI_KAMPUNG_URL') ?: 'https://res.cloudinary.com/ojm5bajr/image/upload/v1794296218/village.jpg',
    ],

    /* Foto di bagian "Identitas Sumatera Utara" (satu provinsi, banyak salam). Kosong / gagal = ilustrasi Rumah Bolon. */
    'identitas' => [
        'foto' => env('IDENTITAS_FOTO_URL') ?: 'https://socialexpat.net/wp-content/uploads/2024/03/Batak-people.jpeg',
    ],

    /*
     | Video latar header halaman Kuliner / Budaya / Destinasi. Potongan (mulai..selesai, detik) diputar berulang & senyap.
     |
     |   kuliner   : TikTok  @jesselynmci8 / 7295341305348017413   00:20 - 00:50  (tiktok-bg.js)
     |   budaya    : YouTube S_FF6NxiYcM                           01:17 - 02:35  (youtube-bg.js)
     |   destinasi : YouTube S_FF6NxiYcM                           07:18 - 09:09  (youtube-bg.js)
     |
     | 'tiktok' dan 'youtube' menerima ID polos ATAU URL penuh. Jika dua-duanya terisi, TikTok dipakai lebih dulu.
     | GANTI DI SINI (atau lewat .env) bila ingin mengganti video / potongan waktunya.
     */
    'header' => [
        'kuliner'   => ['tiktok'  => (env('HEADER_KULINER_TIKTOK') ?: 'https://www.tiktok.com/@jesselynmci8/video/7295341305348017413'),
                        'youtube' => env('HEADER_KULINER_YOUTUBE'),
                        'mulai'   => (int) (env('HEADER_KULINER_MULAI') ?: 20),   'selesai' => (int) (env('HEADER_KULINER_SELESAI') ?: 50)],
        'budaya'    => ['youtube' => (env('HEADER_BUDAYA_YOUTUBE') ?: 'https://youtu.be/S_FF6NxiYcM'),
                        'mulai'   => (int) (env('HEADER_BUDAYA_MULAI') ?: 77),    'selesai' => (int) (env('HEADER_BUDAYA_SELESAI') ?: 155)],   // 1:17 - 2:35
        'destinasi' => ['youtube' => (env('HEADER_DESTINASI_YOUTUBE') ?: 'https://youtu.be/S_FF6NxiYcM'),
                        'mulai'   => (int) (env('HEADER_DESTINASI_MULAI') ?: 438), 'selesai' => (int) (env('HEADER_DESTINASI_SELESAI') ?: 549)], // 7:18 - 9:09
    ],
];
