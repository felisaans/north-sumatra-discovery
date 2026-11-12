@extends('layouts.app')
@section('judul', 'Kuliner Sumatera Utara — North Sumatra Discovery')
@section('meta_deskripsi', 'Katalog kuliner Sumatera Utara: soto Medan, mie gomak, arsik, bika ambon, dan hidangan khas lain beserta ulasan pengunjung.')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/kuliner.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header-video.css') }}">
@endpush

@section('footer_kolom')
    <h6 class="footer-heading">Kuliner Populer</h6>
    <ul class="list-unstyled footer-links mb-0">
        <li><a href="{{ route('kuliner.index', ['q' => 'soto']) }}">Soto Medan</a></li>
        <li><a href="{{ route('kuliner.index', ['q' => 'sidikalang']) }}">Kopi Sidikalang</a></li>
        <li><a href="{{ route('kuliner.index', ['q' => 'arsik']) }}">Arsik Ikan Mas</a></li>
        <li><a href="{{ route('kuliner.index', ['q' => 'bika']) }}">Bika Ambon</a></li>
    </ul>
@endsection

@section('latar', 'kuliner')

@section('isi')
    <header class="hero-banner has-video">
        <x-video-youtube :item="config('video.header.kuliner')" />
        <div class="container">
            <div class="hero-tag-pill">
                <span class="tag-dot"></span>
                <span>Yang dimakan di Sumut</span>
            </div>
            <h1>Cita rasa dari Medan sampai Nias.</h1>
            <p>Dari kedai kopi di kota sampai dapur kampung di dataran tinggi: temukan hidangan dan cerita di baliknya.</p>
        </div>
    </header>

    <div class="container my-5">
        {{-- GET biasa (jalan tanpa JS). live-search.js menambahkan pencarian instan 300 ms --}}
        <form class="filter-section" method="GET" action="{{ route('kuliner.index') }}" data-live-search data-target="#daftarKulinerContainer">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="q" id="inputPencarian" class="form-control" value="{{ $q }}"
                               placeholder="Cari nama kuliner atau bahan utama..." autocomplete="off">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="daerah" id="filterDaerah" class="form-select" aria-label="Filter daerah">
                        <option value="">Semua Daerah</option>
                        @foreach ($daftarDaerah as $d)
                            <option value="{{ $d->slug }}" @selected($daerahAktif === $d->slug)>{{ $d->nama_daerah }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="kategori" id="filterKategori" class="form-select" aria-label="Filter kategori">
                        <option value="">Semua Kategori</option>
                        @foreach ($daftarKategori as $k)
                            <option value="{{ $k->slug }}" @selected($kategoriAktif === $k->slug)>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="urut" id="urutKuliner" class="form-select" aria-label="Urutkan">
                        <option value="">Urut: Nama A–Z</option>
                        <option value="rating" @selected($urutAktif === 'rating')>Urut: Rating tertinggi</option>
                        <option value="terbaru" @selected($urutAktif === 'terbaru')>Urut: Terbaru</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary-custom w-100 h-100 d-flex align-items-center justify-content-center" id="btnFilter" aria-label="Terapkan filter">
                        <i class="fa-solid fa-sliders"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="container pb-5" id="daftarKulinerContainer" aria-live="polite">
        @include('kuliner._daftar')
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/live-search.js') }}" defer></script>
    <script src="{{ asset('assets/js/youtube-bg.js') }}" defer></script>
    <script src="{{ asset('assets/js/tiktok-bg.js') }}" defer></script>
@endpush
