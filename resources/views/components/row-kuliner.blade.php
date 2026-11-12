{{-- Baris editorial daftar Kuliner dengan blob organik. $i = urutan (selang-seling kiri/kanan). Pengganti rowKuliner() --}}
@props(['kuliner', 'i' => 0])
@php $ganjil = $i % 2 === 1; @endphp
<div @class(['row align-items-center editorial-row py-5', 'flex-lg-row-reverse' => $ganjil]) data-daerah="{{ $kuliner->daerah?->slug }}">
    <div @class(['col-lg-6 mb-4 mb-lg-0 editorial-content', 'ps-lg-5' => $ganjil, 'pe-lg-5' => ! $ganjil])>
        <div class="mb-3">
            <span class="badge-kategori mb-2">{{ $kuliner->kategori?->nama_kategori ?: 'Kuliner' }}</span>
            <span class="text-muted small ms-2"><i class="fa-solid fa-location-dot text-teal me-1"></i> {{ $kuliner->daerah?->nama_daerah }}</span>
        </div>
        <h2 class="editorial-title">{{ $kuliner->nama }}</h2>
        <p class="text-muted mb-4 lead fs-6">{{ $kuliner->deskripsi }}</p>
        <div class="d-flex align-items-center gap-4">
            <span class="fw-bold"><i class="fa-solid fa-star"></i> {{ $kuliner->rating_rata ?? '-' }} <span class="text-muted fw-normal font-monospace small">({{ $kuliner->jumlah_ulasan }} ulasan)</span></span>
            <a href="{{ route('kuliner.show', $kuliner) }}" class="btn btn-aceh-outline">Lihat Detail</a>
        </div>
    </div>
    <div class="col-lg-6 organic-shape-container">
        <div @class(['organic-blob', 'coral' => $ganjil])></div>
        <div class="editorial-img-wrap">
            <x-gambar :src="$kuliner->gambar_url" :alt="$kuliner->nama" kelas="editorial-img" fallback="https://placehold.co/600x600/F8E8D0/54205F" />
            <x-lencana-peringkat :peringkat="$kuliner->peringkat" class="badge-peringkat--sudut" />
        </div>
    </div>
</div>
