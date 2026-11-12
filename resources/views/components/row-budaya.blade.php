{{-- Baris Budaya: bingkai bulat berselang-seling. Pengganti rowBudaya() --}}
@props(['budaya', 'i' => 0])
@php
    $genap = $i % 2 === 0;
    $deskripsi = (string) $budaya->deskripsi;
    // Huruf pertama DESKRIPSI jadi drop-cap (versi lama memakai huruf pertama NAMA lalu memotong deskripsi: salah bila huruf awalnya beda)
    $hurufAwal = mb_substr($deskripsi, 0, 1);
    $sisa = mb_substr($deskripsi, 1);
@endphp
<div @class(['row g-5 align-items-center mb-5 budaya-item', 'flex-lg-row-reverse' => ! $genap]) data-kategori="{{ $budaya->kategori?->slug }}">
    <div @class(['col-lg-4 text-center', 'text-lg-start' => $genap, 'text-lg-end' => ! $genap])>
        <div class="ornate-frame-circle mb-3 mb-lg-0">
            <x-gambar :src="$budaya->gambar_url" :alt="$budaya->nama" fallback="https://placehold.co/400x400/2d6a4f/ffffff" />
        </div>
    </div>
    <div class="col-lg-8">
        <div class="custom-card-bg p-4 p-md-5">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <span class="badge-budaya"><i class="fa-solid fa-masks-theater me-1"></i> {{ $budaya->kategori?->nama_kategori ?: 'Budaya' }}</span>
                <span class="text-muted small fw-semibold"><i class="fa-solid fa-location-dot text-teal me-1"></i> {{ $budaya->daerah?->nama_daerah }}</span>
            </div>
            <h3 class="fw-bold mb-3 text-teal font-editorial" style="font-size: 1.8rem;">{{ $budaya->nama }}</h3>
            <p class="text-muted lh-lg mb-4">@if ($hurufAwal !== '')<span class="drop-cap">{{ $hurufAwal }}</span>@endif{{ $sisa }}</p>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3">
                @if (filled($budaya->sorotan))
                    <span class="text-teal small fw-bold"><i class="fa-solid fa-award me-1"></i> {{ $budaya->sorotan }}</span>
                @endif
                {{-- Link video diisi admin (Dashboard -> Budaya & Tradisi -> Link Video). Hanya tautan http(s) yang ditampilkan. --}}
                @if (preg_match('#^https?://#i', (string) $budaya->video_url) === 1)
                    <a href="{{ $budaya->video_url }}" class="budaya-tonton ms-auto" target="_blank" rel="noopener noreferrer">
                        <i class="fa-solid fa-circle-play" aria-hidden="true"></i> Tonton {{ $budaya->nama }}
                        <span class="visually-hidden">(membuka tab baru)</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
