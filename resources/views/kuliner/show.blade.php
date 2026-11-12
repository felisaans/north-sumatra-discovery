@extends('layouts.app')
@section('judul', $kuliner->nama.' - North Sumatra Discovery')
@section('footer_ringkas')@endsection
@section('meta_deskripsi', \Illuminate\Support\Str::limit(strip_tags((string) $kuliner->deskripsi), 155))
@section('meta_gambar', $kuliner->gambar_url)

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/kuliner.css') }}">
@endpush

@php
    $rupiah = fn ($n) => 'Rp '.number_format((int) $n, 0, ',', '.');
    $harga = $kuliner->harga_min !== null
        ? $rupiah($kuliner->harga_min).($kuliner->harga_max ? ' - '.$rupiah($kuliner->harga_max) : '')
        : '';
@endphp

@section('isi')
    {{-- Latar judul = foto kuliner ini sendiri (diberi tint + blur tipis lewat CSS .hero-gambar). Tanda kurung/kutip di URL di-encode agar url() tidak pecah. --}}
    @php $fotoHero = $kuliner->gambar_url ? str_replace(["'", '(', ')', ' '], ['%27', '%28', '%29', '%20'], $kuliner->gambar_url) : null; @endphp
    <header @class(['hero-banner', 'hero-gambar' => $fotoHero]) @if ($fotoHero) style="--hero-foto: url('{{ $fotoHero }}')" @endif>
        <div class="container">
            <span class="badge-kategori mb-3">{{ $kuliner->kategori?->nama_kategori ?: 'Kuliner' }}</span>
            <h1 class="editorial-title">{{ $kuliner->nama }}</h1>
            <p class="text-muted mb-0"><i class="fa-solid fa-location-dot me-1"></i> {{ $kuliner->daerah?->nama_daerah }}</p>
        </div>
    </header>

    <main class="container pb-5 pt-5 kuliner-detail">
        <div class="row align-items-center g-5">
            <div class="col-lg-5 organic-shape-container">
                <div class="organic-blob"></div>
                <div class="editorial-img-wrap">
                    <x-gambar :src="$kuliner->gambar_url" :alt="$kuliner->nama" kelas="editorial-img" fallback="https://placehold.co/600x600/F8E8D0/54205F" :lazy="false" />
                    <x-lencana-peringkat :peringkat="$kuliner->peringkat" class="badge-peringkat--sudut" />
                </div>
            </div>
            <div class="col-lg-7">
                <p class="lead fs-6 text-muted">{{ $kuliner->deskripsi }}</p>
                <ul class="list-unstyled">
                    @if ($kuliner->bahan_utama)
                        <li class="mb-2"><i class="fa-solid fa-bowl-food ico-lokasi me-2"></i><strong>Bahan utama:</strong> {{ $kuliner->bahan_utama }}</li>
                    @endif
                    @if ($harga)
                        <li class="mb-2"><i class="fa-solid fa-tag text-teal me-2"></i><strong>Kisaran harga:</strong> {{ $harga }}</li>
                    @endif
                    <li class="fw-bold"><i class="fa-solid fa-star me-2"></i>{{ $kuliner->rating_rata ?? '-' }} <span class="text-muted fw-normal small">({{ $kuliner->jumlah_ulasan }} ulasan)</span></li>
                </ul>

                {{-- F10: tamu diarahkan ke Masuk; pengguna login: tombol toggle (POST) --}}
                @auth
                    <form method="POST" action="{{ route('favorit.toggle', $kuliner) }}">
                        @csrf
                        <button type="submit" @class(['btn', 'btn-aceh-saved' => $sudahFavorit, 'btn-aceh-heart' => ! $sudahFavorit])>
                            <i class="fa-{{ $sudahFavorit ? 'solid' : 'regular' }} fa-heart me-1"></i>{{ $sudahFavorit ? 'Tersimpan di favorit' : 'Simpan ke favorit' }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('masuk') }}" class="btn btn-aceh-heart"><i class="fa-regular fa-heart me-1"></i>Simpan ke favorit</a>
                @endauth
            </div>
        </div>

        @if ($kuliner->sejarah)
            <h2 class="h4 fw-bold mt-4">Sejarah dan Asal-usul</h2>
            <p class="text-muted" style="white-space:pre-line">{{ $kuliner->sejarah }}</p>
        @endif
        @if ($kuliner->cerita_budaya)
            <h2 class="h4 fw-bold mt-4">Cerita Budaya</h2>
            <p class="text-muted" style="white-space:pre-line">{{ $kuliner->cerita_budaya }}</p>
        @endif

        {{-- Tempat mencicipinya: 1-3 tautan yang diisi admin di dashboard (Kuliner -> Spot 1..3) --}}
        @php $spot = $kuliner->spotRekomendasi(); @endphp
        @if ($spot !== [])
            <h2 class="h4 fw-bold mt-5">Tempat mencicipinya</h2>
            <p class="small text-muted mb-3">Tempat yang bisa kamu kunjungi untuk mencicipi {{ $kuliner->nama }}.</p>
            <div class="d-flex flex-wrap gap-2 spot-kuliner">
                @foreach ($spot as $s)
                    <a href="{{ $s['url'] }}" class="btn btn-aceh-outline px-3 py-2 spot-kuliner-item" target="_blank" rel="noopener noreferrer nofollow">
                        <i class="fa-solid fa-location-dot text-teal me-2" aria-hidden="true"></i>{{ $s['nama'] }}
                        <i class="fa-solid fa-arrow-up-right-from-square ms-2 small text-muted" aria-hidden="true"></i>
                        <span class="visually-hidden">(membuka tab baru)</span>
                    </a>
                @endforeach
            </div>
        @endif

        @if ($terkait->isNotEmpty())
            <h2 class="h4 fw-bold mt-5">Kuliner lain yang mungkin kamu suka</h2>
            <div class="d-flex flex-wrap gap-2 mt-3">
                @foreach ($terkait as $t)
                    <a href="{{ route('kuliner.show', $t) }}" class="btn btn-aceh-outline px-3 py-2">
                        {{ $t->nama }} <span class="text-muted small">· {{ $t->daerah?->nama_daerah }}</span>
                    </a>
                @endforeach
            </div>
        @endif

        <hr class="my-5">
        <h2 class="h4 fw-bold" id="ulasan">Ulasan</h2>

        <div class="filter-section mb-4">
            @auth
                <form method="POST" action="{{ route('ulasan.store', $kuliner) }}#ulasan">
                    @csrf
                    <label class="form-label fw-semibold" for="fRating">{{ $ulasanSaya ? 'Ubah penilaianmu' : 'Beri penilaian' }}</label>
                    <select id="fRating" name="rating" class="form-select mb-1 @error('rating') is-invalid @enderror">
                        @foreach ([5, 4, 3, 2, 1] as $n)
                            <option value="{{ $n }}" @selected((int) old('rating', $ulasanSaya?->rating ?? 5) === $n)>{{ str_repeat('★', $n) }}{{ str_repeat('☆', 5 - $n) }}</option>
                        @endforeach
                    </select>
                    @error('rating')<div class="small text-danger mb-2">{{ $message }}</div>@enderror

                    <textarea id="fKomentar" name="komentar" class="form-control mt-2 mb-1 @error('komentar') is-invalid @enderror" rows="3" maxlength="1000"
                              placeholder="Ceritakan pengalaman kamu...">{{ old('komentar', $ulasanSaya?->komentar) }}</textarea>
                    @error('komentar')<div class="small text-danger mb-2">{{ $message }}</div>@enderror

                    <button class="btn btn-primary-custom mt-2" type="submit">Kirim Ulasan</button>
                </form>
            @else
                <p class="mb-0">Silakan <a href="{{ route('masuk') }}">masuk</a> untuk memberi ulasan dan menyimpan favorit.</p>
            @endauth
        </div>

        @forelse ($ulasan as $u)
            <div class="py-3 ulasan-item">
                <strong>{{ $u->user?->name ?: 'Pengguna' }}</strong>
                <span class="ico-bintang ms-2"><x-bintang :n="$u->rating" /></span>
                <div class="small text-muted">{{ $u->created_at->format('j/n/Y') }}</div>
                <p class="mb-0 mt-1">{{ $u->komentar }}</p>
            </div>
        @empty
            <p class="text-muted">Belum ada ulasan. Mau jadi yang pertama?</p>
        @endforelse
    </main>
@endsection
