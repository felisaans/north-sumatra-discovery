@extends('layouts.app')
@section('judul', 'Tempat Singgah — North Sumatra Discovery')
@section('meta_deskripsi', 'Tempat singgah di Sumatera Utara: situs sejarah, alam, dan desa adat dekat kuliner dan budaya.')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/destinasi.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header-video.css') }}">
@endpush

@section('footer_kolom')
    <h6 class="footer-heading">Destinasi Populer</h6>
    <ul class="list-unstyled footer-links mb-0">
        <li><a href="{{ route('destinasi.index', ['daerah' => 'kota-medan']) }}">Istana Maimun</a></li>
        <li><a href="{{ route('destinasi.index', ['daerah' => 'langkat']) }}">Bukit Lawang</a></li>
        <li><a href="{{ route('destinasi.index', ['daerah' => 'toba']) }}">Danau Toba</a></li>
        <li><a href="{{ route('destinasi.index', ['daerah' => 'karo']) }}">Berastagi</a></li>
    </ul>
@endsection

@section('latar', 'destinasi')

@section('isi')
    <section class="page-hero has-video">
        <x-video-youtube :item="config('video.header.destinasi')" />
        <div class="container">
            <div class="hero-tag-pill">
                <span class="tag-dot"></span>
                <span>Pelengkap perjalanan</span>
            </div>
            <h1>Tempat singgah di antara kuliner dan budaya.</h1>
            <p class="lead-custom">
                Pelengkap perjalananmu: tempat bersejarah, alam, dan desa adat yang dekat dengan hidangan dan tradisi yang kamu cari.
            </p>
        </div>
    </section>

    {{-- Chip berupa tautan ?daerah=slug (jalan tanpa JS); chip-filter.js menggantinya jadi tanpa reload --}}
    <section class="container my-5 py-3">
        <div class="d-flex flex-wrap justify-content-center gap-2" id="filterDaerahChipsDestinasi"
             data-chip-filter data-chip-remote="#daftarDestinasiContainer">
            <x-chip-daerah slug="semua" teks="Semua Daerah" :aktif="$daerahAktif === ''" :href="route('destinasi.index')" />
            @foreach ($daerahChip as $d)
                <x-chip-daerah :slug="$d->slug" :teks="$d->nama_daerah" :aktif="$daerahAktif === $d->slug" :href="route('destinasi.index', ['daerah' => $d->slug])" />
            @endforeach
        </div>
    </section>

    <div class="container dest-container" id="daftarDestinasiContainer" aria-live="polite">
        @include('destinasi._daftar')
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/live-search.js') }}" defer></script>
    <script src="{{ asset('assets/js/youtube-bg.js') }}" defer></script>
    <script src="{{ asset('assets/js/chip-filter.js') }}" defer></script>
@endpush
