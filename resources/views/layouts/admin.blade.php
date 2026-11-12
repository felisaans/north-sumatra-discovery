<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tema dark/light sama dengan situs publik (disimpan di localStorage 'tema'; default gelap).
         Dipasang di <head> sebelum CSS agar tidak berkedip. --}}
    <script>
        (function () {
            var tema = 'dark';
            try { var s = localStorage.getItem('tema'); if (s === 'light' || s === 'dark') tema = s; } catch (e) {}
            var h = document.documentElement;
            h.setAttribute('data-theme', tema);
            h.setAttribute('data-bs-theme', tema);
        })();
    </script>
    <meta name="theme-color" content="#17131A">
    <title>@yield('judul', 'Dashboard Admin - North Sumatra Discovery')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Schibsted+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/laravel.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/editorial.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dark.css') }}">
    <script src="{{ asset('assets/js/tema.js') }}" defer></script>
</head>
<body>

    {{-- Navbar admin = navbar situs (brand-icon bulat + Playfair), ditambah label Admin --}}
    <nav class="navbar navbar-expand-lg sticky-top aceh-navbar admin-navbar">
        <div class="container-fluid px-3 px-lg-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('beranda') }}">
                <x-logo-ns />
                <span class="brand-text">North Sumatra Discovery <span class="brand-sub">Admin</span></span>
            </a>
            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <button type="button" class="tema-toggle js-tema-toggle" aria-label="Ganti tema" aria-pressed="true" title="Ganti tema">
                    <i class="fa-solid fa-moon ikon-bulan" aria-hidden="true"></i><i class="fa-solid fa-sun ikon-matahari" aria-hidden="true"></i>
                </button>
                <span class="admin-user d-none d-md-inline-flex align-items-center gap-2">
                    <span class="admin-avatar" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr((string) auth()->user()->email, 0, 1)) }}</span>
                    <span class="small">{{ auth()->user()->email }}</span>
                </span>
                <a href="{{ route('beranda') }}" class="btn btn-sm btn-aceh-outline">Lihat Situs</a>
                <form method="POST" action="{{ route('keluar') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-aceh-danger">Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    @include('partials.flash')

    @yield('isi')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/flash.js') }}" defer></script>
    @stack('js')
</body>
</html>
