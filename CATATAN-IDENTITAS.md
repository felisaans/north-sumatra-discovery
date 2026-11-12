# North Sumatra Discovery: catatan identitas

Sistem tetap sama dengan Aceh Discovery (Laravel, route, controller, migration, admin, favorit, ulasan, pengalaman). Yang berubah:

- **Palet:** sekarang **Ulos Electric (Balanced)**, lihat `PANDUAN-WARNA-ULOS-ELECTRIC.md`. Token ada di `style.css` (terang) dan `dark.css` (gelap). Palet netral sebelumnya sudah diganti.
- **Font:** Schibsted Grotesk menggantikan Playfair Display dan Plus Jakarta Sans.
- **Logo dan favicon:** atap Rumah Bolon di atas garis air (`partials/navbar.blade.php`, `public/favicon.svg`).
- **Lapisan identitas:** `public/assets/css/identitas-sumut.css` (dimuat paling akhir di `layouts/app.blade.php`): sudut lebih lembut, judul tanpa kata beraksen, satu animasi masuk di beranda.
- **Fokus konten:** kuliner dan budaya & tradisi di depan. Menu "Destinasi Wisata" menjadi "Tempat Singgah" (terakhir). CTA beranda mengarah ke Kuliner.
- **Data awal:** daerah, kuliner, budaya, destinasi, dan berita di `database/seeders` sudah data Sumatera Utara (contoh, mohon dicek ulang).

## Yang masih perlu diisi
- Foto dan video asli: semua gambar masih placeholder (`placehold.co`). Isi `HERO_*` dan `EDITORIAL_*` di `.env`, dan ganti URL `latar` di `config/video.php`.
- Gambar kolase di bagian "Identitas Kebudayaan" beranda (`beranda.blade.php`).
- Logo PNG (`public/favicon-192.png`, `og-image.jpg`) belum diperbarui; SVG sudah.
- Setelah pindah dari Aceh: `php artisan migrate:fresh --seed`.

## Polesan beranda
- Hero kanan: slider **kota ↔ kampung** (`public/assets/js/dua-sisi.js`). Kalau `HERO_VIDEO_URL` atau `HERO_POSTER_URL` diisi di `.env`, yang tampil video/foto itu.
- Bingkai kubah masjid (identitas Aceh) dan kartu mengambang dihapus; hero dibuat datar dan lega.
- Angka hero kini dari database: jumlah daerah, kuliner, dan budaya.
- Sorotan dan kolase memakai ilustrasi SVG sebagai pengganti foto sementara (`.scene-ph`). Foto atau video asli otomatis menutupinya.
- Bagian Kuliner dan Budaya diberi latar selang-seling netral dan jarak antarbagian yang seragam.

## Video sorotan
- **Utama:** URL video `.mp4` dari hosting online mana saja (mis. Cloudinary) lewat `EDITORIAL_<NAMA>_VIDEO_URL` di `.env`, sama seperti Aceh Discovery. Rasio bingkai diatur di `config/video.php` (`lebar`/`tinggi`).
- **Opsional:** YouTube, bila `EDITORIAL_<NAMA>_YOUTUBE` diisi.
- YouTube: isi `EDITORIAL_<NAMA>_YOUTUBE` di `.env` dengan link atau ID video, plus detik `MULAI` dan `SELESAI` untuk potongan yang diulang. Diputar senyap tanpa kontrol (pakai `youtube-bg.js`). Kalau kosong, tampil ilustrasi.
- Video latar header Kuliner/Budaya/Destinasi sebelumnya memakai ID video versi Aceh. Sekarang dikosongkan; isi lewat `HEADER_*_YOUTUBE`.
- Pastikan video mengizinkan penyematan (embed), kalau tidak, bingkai kembali ke ilustrasi.

## Suara tulisan (copywriting)
Prinsip: ramah, ringan, kalimat pendek, memakai "kamu" (bukan "Anda"), dan bermain di dua sisi (kota/kampung, piring/panggung adat). Hindari kata brosur seperti "otentik", "warisan luhur", "eksplorasi", "keharuman".
- Judul bagian: "Mulai dari sini", "Lapar? Mulai dari sini", "Budaya yang masih hidup", "Dua sisi, satu rumah", "Dari yang sudah datang", "Siap berangkat?"
- Tombol: "Cicipi kopinya", "Intip Rumah Bolon", "Kenali tor-tor", "Tulis ceritamu".
- Salam penyambut: "Horas!" (hero dan footer), serta "Horas, Mejuah-juah, Ya'ahowu" (bagian identitas).
- Semua teks ada di `resources/views/` (terutama `beranda.blade.php`), jadi mudah disesuaikan.

## Penyelarasan warna Ulos Electric
- **File yang diubah:** `style.css` (token terang), `dark.css` (token gelap), `identitas-sumut.css` (state komponen), `beranda.css`, `favicon.svg`, `dua-sisi.js` (warna ilustrasi), seeder dan config (warna gambar placeholder).
- **Nama variabel lama dipertahankan** (`--c-mint`, `--c-night`, `--c-porcelain`, `--color-teal`, `--color-accent`, `--btn-bg`, `--rgb-teal`, variabel Bootstrap, dst.). Hanya nilainya yang berubah, sesuai panduan.
- **Token baru** (bagian bawah blok `:root`): `--btn-active-bg`, `--accent-btn-*`, `--outline-*`, `--disabled-*`, `--link-color`, `--card-border`, `--focus-ring`, dll.
- **Radius, font, layout, dan animasi tidak disentuh.**
- Warna medali peringkat (emas/perak/perunggu) di `editorial.css` sengaja dibiarkan karena bermakna semantik.
- **Belum diuji di browser.** Periksa kedua tema (terang dan gelap), terutama kontras teks kecil di atas Vermilion dan garis tipis `--bs-border-color` yang sekarang berwarna (`#EAD9D5` terang) padahal sebelumnya transparan.

## Sinkron dengan versi hosting (htdocs)
Folder ini dibuat dari `htdocs.zip` (versi terbaru di hosting) yang dipetakan ke struktur Laravel standar: isi `htdocs/laravel/*` menjadi akar project, sedangkan file publik di `htdocs/` (assets, favicon, dst.) menjadi `public/`. `index.php` dan `.htaccess` memakai versi standar Laravel (bukan versi hosting).
- Yang ikut dari hosting: komponen `logo-ns` (logo konsisten di navbar, footer, admin), `tiktok-bg.js`, bagian identitas baru (Rumah Bolon + kartu salam), foto slider kota/kampung, empat sorotan editorial terbaru (Tor-tor, Arsik, Danau Toba, Bika Ambon), ikon favicon baru, dan seeder terbaru.
- Ditambahkan di atasnya: perapian garis tipis dan kontras teks (token `--divider`, `--danger-text`, `--success-text`, `--placeholder`; link tema gelap diganti ke soft pink agar kontras >= 4.5:1), jendela menyala di ilustrasi kota, dan logo atap tidak berputar saat hover.
- Kolase lama dan komponen `logo` buatanku tidak dipakai karena sudah digantikan versi hosting.
