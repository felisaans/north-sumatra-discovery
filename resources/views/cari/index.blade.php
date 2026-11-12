@extends('layouts.app')
@section('judul', ($q !== '' ? 'Hasil pencarian "'.$q.'"' : 'Pencarian').' — North Sumatra Discovery')
@section('meta_deskripsi', 'Cari kuliner, budaya, dan tempat singgah di Sumatera Utara sekaligus dalam satu pencarian.')

@push('css')
    {{-- beranda.css: kartu (.discovery-card-modern) & kotak cari (.search-box-pill) dipakai ulang agar konsisten --}}
    <link rel="stylesheet" href="{{ asset('assets/css/beranda.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/cari.css') }}">
@endpush

@section('isi')
    <header class="cari-hero">
        <div class="container">
            <span class="text-uppercase tracking-wider text-teal fw-bold small">Pencarian</span>
            <h1 class="font-editorial fw-bold mt-2 mb-4">
                @if ($q !== '')
                    Hasil untuk <em>&ldquo;{{ $q }}&rdquo;</em>
                @else
                    Cari apa saja
                @endif
            </h1>

            {{-- GET biasa; satu kata kunci mencari kuliner + budaya + destinasi --}}
            <form action="{{ route('cari.index') }}" method="GET" class="search-box-container cari-form" role="search">
                <div class="input-group search-box-pill">
                    <span class="input-group-text bg-transparent border-0 text-teal ps-2">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="search" name="q" value="{{ $q }}" maxlength="100" autocomplete="off"
                           class="form-control" placeholder="Cari soto Medan, tor-tor, ulos, Samosir..." aria-label="Kata kunci pencarian">
                    <button type="submit" class="btn btn-aceh-primary">Telusuri</button>
                </div>
            </form>

            @if ($q !== '' && $total > 0)
                <p class="text-muted mt-4 mb-3">Ditemukan <strong>{{ $total }}</strong> hasil di seluruh kategori.</p>
                <nav class="cari-ringkas" aria-label="Lompat ke kelompok hasil">
                    @foreach ([['kuliner', 'Kuliner', $kuliner], ['budaya', 'Budaya', $budaya], ['destinasi', 'Destinasi', $destinasi]] as [$id, $nama, $data])
                        @if ($data->count() > 0)
                            <a href="#hasil-{{ $id }}" class="cari-chip">{{ $nama }} <span>{{ $data->count() }}</span></a>
                        @endif
                    @endforeach
                </nav>
            @endif
        </div>
    </header>

    <div class="container pb-5">
        @if ($q === '')
            <x-pesan-kosong>Ketik kata kunci untuk mencari kuliner, budaya, dan destinasi sekaligus.</x-pesan-kosong>
        @elseif ($total === 0)
            <x-pesan-kosong class="mb-2">Tidak ada hasil untuk &ldquo;{{ $q }}&rdquo;. Coba kata kunci lain, misalnya:</x-pesan-kosong>
            <div class="d-flex flex-wrap justify-content-center gap-2 pb-5">
                @foreach (['soto', 'tor-tor', 'ulos', 'arsik', 'toba'] as $saran)
                    <a href="{{ route('cari.index', ['q' => $saran]) }}" class="cari-chip">{{ $saran }}</a>
                @endforeach
            </div>
        @else
            {{-- ===== KULINER ===== --}}
            @if ($kuliner->isNotEmpty())
                <section class="cari-kelompok" id="hasil-kuliner">
                    <div class="cari-kelompok-head">
                        <h2 class="font-editorial fw-bold"><i class="fa-solid fa-bowl-food text-teal me-2"></i>Kuliner <small>({{ $kuliner->count() }})</small></h2>
                        @if ($kuliner->count() > $batas)
                            <a href="{{ route('kuliner.index', ['q' => $q]) }}" class="btn btn-sm btn-aceh-outline">Lihat semua kuliner</a>
                        @endif
                    </div>
                    <div class="row g-4">
                        @foreach ($kuliner->take($batas) as $k)
                            <div class="col-12 col-sm-6 col-lg-3">
                                <x-card-mini tipe="kuliner" :href="route('kuliner.show', $k)" :badge="$k->kategori?->nama_kategori ?: 'Kuliner'"
                                             :lokasi="$k->daerah?->nama_daerah" :judul="$k->nama" :deskripsi="\Illuminate\Support\Str::limit($k->deskripsi, 110)"
                                             :gambar="$k->gambar_url" tombolTeks="Lihat Detail" :peringkat="$k->peringkat" />
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- ===== BUDAYA ===== --}}
            @if ($budaya->isNotEmpty())
                <section class="cari-kelompok" id="hasil-budaya">
                    <div class="cari-kelompok-head">
                        <h2 class="font-editorial fw-bold"><i class="fa-solid fa-masks-theater text-teal me-2"></i>Budaya <small>({{ $budaya->count() }})</small></h2>
                        <a href="{{ route('budaya.index', ['q' => $q]) }}" class="btn btn-sm btn-aceh-outline">Buka di halaman Budaya</a>
                    </div>
                    <div class="row g-4">
                        @foreach ($budaya->take($batas) as $b)
                            <div class="col-12 col-sm-6 col-lg-3">
                                <x-card-mini tipe="budaya" :href="route('budaya.index', ['q' => $b->nama])" :badge="$b->kategori?->nama_kategori ?: 'Budaya'"
                                             :lokasi="$b->daerah?->nama_daerah" :judul="$b->nama" :deskripsi="\Illuminate\Support\Str::limit($b->deskripsi, 110)"
                                             :gambar="$b->gambar_url" tombolTeks="Lihat Budaya" />
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- ===== DESTINASI ===== --}}
            @if ($destinasi->isNotEmpty())
                <section class="cari-kelompok" id="hasil-destinasi">
                    <div class="cari-kelompok-head">
                        <h2 class="font-editorial fw-bold"><i class="fa-solid fa-mountain-sun text-teal me-2"></i>Destinasi <small>({{ $destinasi->count() }})</small></h2>
                        <a href="{{ route('destinasi.index') }}" class="btn btn-sm btn-aceh-outline">Buka halaman Destinasi</a>
                    </div>
                    <div class="row g-4">
                        @foreach ($destinasi->take($batas) as $d)
                            <div class="col-12 col-sm-6 col-lg-3">
                                <x-card-mini tipe="destinasi" :href="route('destinasi.index', ['daerah' => $d->daerah?->slug])" :badge="$d->jenis_wisata ?: 'Destinasi'"
                                             :lokasi="$d->daerah?->nama_daerah" :judul="$d->nama" :deskripsi="\Illuminate\Support\Str::limit($d->deskripsi, 110)"
                                             :gambar="$d->gambar_url" tombolTeks="Lihat Destinasi" />
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        @endif
    </div>
@endsection
