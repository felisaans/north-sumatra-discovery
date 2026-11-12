<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- TEMA (dark/light). Skrip kecil ini HARUS di <head> sebelum CSS agar tidak ada "kedip terang" saat load.
         DEFAULT = DARK; pilihan user disimpan di localStorage ('tema').
         Tiny inline script sets the theme before first paint (default dark, remembers choice). --}}
    <script>
        (function () {
            var tema = 'dark';                                   // GANTI DI SINI: 'light' untuk default terang
            try { var s = localStorage.getItem('tema'); if (s === 'light' || s === 'dark') tema = s; } catch (e) {}
            var h = document.documentElement;
            h.setAttribute('data-theme', tema);
            h.setAttribute('data-bs-theme', tema);
            // Reveal-on-scroll aktif hanya bila JS hidup & pengguna tidak meminta reduced-motion.
            // Fail-safe: bila reveal.js gagal termuat dalam 5 detik, semua konten ditampilkan biasa.
            if (!(window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches)) {
                h.classList.add('js-reveal');
                setTimeout(function () { if (!window.__rv) h.classList.remove('js-reveal'); }, 5000);
            }
        })();
    </script>
    <meta name="theme-color" content="#17131A">
    <title>@yield('judul', 'North Sumatra Discovery — Kuliner, Budaya & Tradisi Sumatera Utara')</title>

    {{-- ============================================================
         SEO & PRATINJAU LINK (WhatsApp, Telegram, Facebook, X, Discord)
         Link preview meta (title, description, image, icon)
         Halaman bisa menimpa lewat @section('judul' / 'meta_deskripsi' / 'meta_gambar').
         ============================================================ --}}
    @php
        // Judul & deskripsi / Title & description
        $metaJudul = trim(View::yieldContent('judul')) ?: 'North Sumatra Discovery — Kuliner, Budaya & Tradisi Sumatera Utara';
        $metaDeskripsi = trim(View::yieldContent('meta_deskripsi')) ?: 'Jelajahi kuliner, budaya, dan tradisi Sumatera Utara dari sisi kota sampai sisi kampung.';

        // Gambar preview: pakai milik halaman, kalau kosong pakai gambar DEFAULT
        // Preview image: page-specific, falls back to a default image
        $metaGambar = trim(View::yieldContent('meta_gambar')) ?: 'assets/img/og-image.jpg'; // GANTI DI SINI (file di public/assets/img/og-image.jpg, 1200x630 px)
    @endphp
    <meta name="description" content="{{ $metaDeskripsi }}">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Ikon / Favicons: SVG untuk browser modern, PNG untuk crawler & perangkat lain --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon-192.png') }}"> {{-- GANTI DI SINI (file di public/favicon-192.png) --}}
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}"> {{-- GANTI DI SINI (file di public/apple-touch-icon.png, 180x180 px) --}}

    {{-- Open Graph (dipakai WhatsApp, Telegram, Facebook, Discord) --}}
    <meta property="og:site_name" content="North Sumatra Discovery">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">
    <meta property="og:title" content="{{ $metaJudul }}">
    <meta property="og:description" content="{{ $metaDeskripsi }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ url($metaGambar) }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="North Sumatra Discovery — Kuliner, Budaya & Tradisi Sumatera Utara">

    {{-- Twitter / X card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaJudul }}">
    <meta name="twitter:description" content="{{ $metaDeskripsi }}">
    <meta name="twitter:image" content="{{ url($metaGambar) }}">

    {{-- Bootstrap, Google Fonts & Font Awesome dari CDN (sama seperti versi lama; tanpa npm/Vite) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Schibsted+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- CSS lama dipindah apa adanya ke public/assets/css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('css')
    <link rel="stylesheet" href="{{ asset('assets/css/laravel.css') }}">
    {{-- editorial.css: bentuk persegi, warna semantik (bintang/love/pin), tombol kaca --}}
    <link rel="stylesheet" href="{{ asset('assets/css/editorial.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/reveal.css') }}">
    {{-- latar-halaman.css: foto latar fixed untuk Kuliner, Budaya, Destinasi (aktif bila halaman mengisi @section('latar')) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/latar-halaman.css') }}">
    {{-- dark.css SELALU terakhir supaya bisa menimpa CSS halaman --}}
    <link rel="stylesheet" href="{{ asset('assets/css/dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/identitas-sumut.css') }}">
    <script src="{{ asset('assets/js/tema.js') }}" defer></script>
</head>
<body>

    <a href="#konten" class="visually-hidden-focusable skip-link">Lewati ke konten utama</a>

    {{-- Latar foto fixed (hanya halaman yang mengisi @section('latar', 'kuliner|budaya|destinasi')) --}}
    @hasSection('latar')
        <div class="latar-halaman latar-{{ trim($__env->yieldContent('latar')) }}" aria-hidden="true"></div>
    @endif

    @include('partials.navbar')
    @include('partials.flash')

    <div id="konten" tabindex="-1">
        @yield('isi')
    </div>

    @hasSection('footer_ringkas')
        @include('partials.footer-ringkas')
    @else
        @include('partials.footer')
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/flash.js') }}" defer></script>
    <script src="{{ asset('assets/js/avatar.js') }}" defer></script>
    <script src="{{ asset('assets/js/hover-fx.js') }}" defer></script>
    <script src="{{ asset('assets/js/reveal.js') }}"></script>
    @stack('js')
</body>
</html>