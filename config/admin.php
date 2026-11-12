<?php

use App\Models\Berita;
use App\Models\Budaya;
use App\Models\Daerah;
use App\Models\Destinasi;
use App\Models\Kategori;
use App\Models\Kuliner;
use App\Models\Pengalaman;

/*
|--------------------------------------------------------------------------
| Konfigurasi modul Dashboard Admin
|--------------------------------------------------------------------------
| Setara objek RESOURCES di admin/assets/admin.js lama.
| Cukup ubah / tambah di sini untuk mengubah kolom tabel, isi form, dan aturan validasi.
| Dibaca oleh App\Http\Controllers\Admin\ResourceController dan routes/web.php.
|
| Tipe field: text | textarea | number | date | select | select-fk | image-url | url
*/

$opsiTipeKategori = [
    ['value' => 'kuliner', 'label' => 'Kuliner'],
    ['value' => 'budaya', 'label' => 'Budaya'],
    ['value' => 'destinasi', 'label' => 'Destinasi'],
];

return [

    'modul' => [

        'daerah' => [
            'label' => 'Daerah', 'icon' => 'fa-map-location-dot',
            'model' => Daerah::class,
            'order' => 'nama_daerah',
            'columns' => [
                ['key' => 'nama_daerah', 'label' => 'Nama Daerah'],
                ['key' => 'slug', 'label' => 'Slug'],
            ],
            'fields' => [
                ['name' => 'nama_daerah', 'label' => 'Nama Daerah', 'type' => 'text', 'required' => true],
                ['name' => 'slug', 'label' => 'Slug', 'hint' => 'unik, huruf kecil, pakai -', 'type' => 'text', 'required' => true],
            ],
        ],

        'kategori' => [
            'label' => 'Kategori', 'icon' => 'fa-tags',
            'model' => Kategori::class,
            'order' => 'nama_kategori',
            'columns' => [
                ['key' => 'nama_kategori', 'label' => 'Nama Kategori'],
                ['key' => 'slug', 'label' => 'Slug'],
                ['key' => 'tipe', 'label' => 'Tipe'],
            ],
            'fields' => [
                ['name' => 'nama_kategori', 'label' => 'Nama Kategori', 'type' => 'text', 'required' => true],
                ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'required' => true],
                ['name' => 'tipe', 'label' => 'Tipe Konten', 'type' => 'select', 'required' => true, 'options' => $opsiTipeKategori],
            ],
        ],

        'kuliner' => [
            'label' => 'Kuliner', 'icon' => 'fa-bowl-food',
            'model' => Kuliner::class,
            'order' => 'nama',
            'with' => ['daerah', 'kategori'],
            'columns' => [
                ['key' => 'gambar', 'label' => 'Foto', 'type' => 'image'],
                ['key' => 'nama', 'label' => 'Nama'],
                ['key' => 'daerah.nama_daerah', 'label' => 'Daerah'],
                ['key' => 'kategori.nama_kategori', 'label' => 'Kategori'],
            ],
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
                ['name' => 'slug', 'label' => 'Slug', 'hint' => 'unik', 'type' => 'text', 'required' => true],
                ['name' => 'daerah_id', 'label' => 'Daerah', 'type' => 'select-fk', 'fk' => 'daerah', 'required' => true],
                ['name' => 'kategori_id', 'label' => 'Kategori', 'type' => 'select-fk', 'fk' => 'kategori', 'fkTipe' => 'kuliner', 'required' => true],
                ['name' => 'gambar', 'label' => 'URL Gambar', 'type' => 'image-url'],
                ['name' => 'deskripsi', 'label' => 'Deskripsi', 'type' => 'textarea'],
                ['name' => 'bahan_utama', 'label' => 'Bahan Utama', 'type' => 'text'],
                ['name' => 'harga_min', 'label' => 'Harga Minimum (Rp)', 'type' => 'number'],
                ['name' => 'harga_max', 'label' => 'Harga Maksimum (Rp)', 'type' => 'number'],
                ['name' => 'sejarah', 'label' => 'Sejarah / Asal-usul', 'type' => 'textarea'],
                ['name' => 'cerita_budaya', 'label' => 'Cerita Budaya', 'type' => 'textarea'],
                // Rekomendasi Spot Kuliner (tampil di detail kuliner). Isi 1-3 spot; yang tautannya kosong tidak ditampilkan
                ['name' => 'spot_1_nama', 'label' => 'Spot 1 - Nama tempat', 'hint' => 'mis. "Mie Razali"', 'type' => 'text', 'max' => 100],
                ['name' => 'spot_1_url', 'label' => 'Spot 1 - Link', 'hint' => 'Google Maps / situs', 'type' => 'url'],
                ['name' => 'spot_2_nama', 'label' => 'Spot 2 - Nama tempat', 'type' => 'text', 'max' => 100],
                ['name' => 'spot_2_url', 'label' => 'Spot 2 - Link', 'type' => 'url'],
                ['name' => 'spot_3_nama', 'label' => 'Spot 3 - Nama tempat', 'type' => 'text', 'max' => 100],
                ['name' => 'spot_3_url', 'label' => 'Spot 3 - Link', 'type' => 'url'],
            ],
        ],

        'budaya' => [
            'label' => 'Budaya & Tradisi', 'icon' => 'fa-masks-theater',
            'model' => Budaya::class,
            'order' => 'nama',
            'with' => ['daerah', 'kategori'],
            'columns' => [
                ['key' => 'gambar', 'label' => 'Foto', 'type' => 'image'],
                ['key' => 'nama', 'label' => 'Nama'],
                ['key' => 'daerah.nama_daerah', 'label' => 'Daerah'],
                ['key' => 'kategori.nama_kategori', 'label' => 'Kategori'],
            ],
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
                ['name' => 'slug', 'label' => 'Slug', 'hint' => 'unik', 'type' => 'text', 'required' => true],
                ['name' => 'daerah_id', 'label' => 'Daerah', 'type' => 'select-fk', 'fk' => 'daerah', 'required' => true],
                ['name' => 'kategori_id', 'label' => 'Kategori', 'type' => 'select-fk', 'fk' => 'kategori', 'fkTipe' => 'budaya', 'required' => true],
                ['name' => 'gambar', 'label' => 'URL Gambar', 'type' => 'image-url'],
                ['name' => 'deskripsi', 'label' => 'Deskripsi', 'type' => 'textarea', 'required' => true],
                ['name' => 'sorotan', 'label' => 'Sorotan singkat', 'hint' => 'mis. "Pengakuan Resmi UNESCO"', 'type' => 'text'],
                ['name' => 'video_url', 'label' => 'Link Video "Tonton"', 'hint' => 'YouTube / lainnya, boleh kosong', 'type' => 'url'],
            ],
        ],

        'destinasi' => [
            'label' => 'Destinasi Wisata', 'icon' => 'fa-mountain-sun',
            'model' => Destinasi::class,
            'order' => 'nama',
            'with' => ['daerah', 'kategori'],
            'columns' => [
                ['key' => 'gambar', 'label' => 'Foto', 'type' => 'image'],
                ['key' => 'nama', 'label' => 'Nama'],
                ['key' => 'daerah.nama_daerah', 'label' => 'Daerah'],
                ['key' => 'jenis_wisata', 'label' => 'Jenis Wisata'],
            ],
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
                ['name' => 'slug', 'label' => 'Slug', 'hint' => 'unik', 'type' => 'text', 'required' => true],
                ['name' => 'daerah_id', 'label' => 'Daerah', 'type' => 'select-fk', 'fk' => 'daerah', 'required' => true],
                ['name' => 'kategori_id', 'label' => 'Kategori', 'type' => 'select-fk', 'fk' => 'kategori', 'fkTipe' => 'destinasi', 'required' => true],
                ['name' => 'jenis_wisata', 'label' => 'Label Jenis Wisata', 'hint' => 'mis. "Wisata Pantai"', 'type' => 'text'],
                ['name' => 'maps_url', 'label' => 'Link Google Maps', 'hint' => 'tombol "Lihat di Maps"', 'type' => 'url'],
                ['name' => 'gambar', 'label' => 'URL Gambar', 'type' => 'image-url'],
                ['name' => 'deskripsi', 'label' => 'Deskripsi', 'type' => 'textarea', 'required' => true],
            ],
        ],

        'berita' => [
            'label' => 'Cerita & Berita', 'icon' => 'fa-bullhorn',
            'model' => Berita::class,
            'order' => 'tanggal', 'order_asc' => false,
            'columns' => [
                ['key' => 'judul', 'label' => 'Judul'],
                ['key' => 'label', 'label' => 'Label'],
                ['key' => 'tanggal', 'label' => 'Tanggal', 'type' => 'date'],
            ],
            'fields' => [
                ['name' => 'judul', 'label' => 'Judul', 'type' => 'text', 'required' => true, 'max' => 200],
                ['name' => 'label', 'label' => 'Label Badge', 'hint' => 'mis. "Kuliner Legendaris"', 'type' => 'text', 'max' => 100],
                ['name' => 'tanggal', 'label' => 'Tanggal', 'type' => 'date', 'required' => true],
                ['name' => 'ringkasan', 'label' => 'Ringkasan', 'type' => 'textarea', 'required' => true],
            ],
        ],

        'pengalaman' => [
            'label' => 'Pengalaman Wisatawan', 'icon' => 'fa-camera',
            'model' => Pengalaman::class,
            'order' => 'created_at', 'order_asc' => false,
            'columns' => [
                ['key' => 'foto', 'label' => 'Foto', 'type' => 'image'],
                ['key' => 'nama', 'label' => 'Nama'],
                ['key' => 'teks', 'label' => 'Cerita'],
            ],
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true, 'max' => 100],
                ['name' => 'teks', 'label' => 'Cerita', 'type' => 'textarea', 'required' => true],
            ],
            // Kiriman publik dari beranda: admin hanya boleh ubah / hapus (tanpa tombol Tambah)
            'hanya_moderasi' => true,
            // Saat baris dihapus, berkas foto di disk 'public' ikut dihapus (kolom ini berisi path-nya)
            'hapus_berkas' => 'foto',
        ],
    ],
];
