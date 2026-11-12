{{-- Kartu kecil grid Beranda ("Kuliner Populer" & "Budaya Pilihan"). Pengganti cardMini() di components.js --}}
@props(['href', 'badge', 'tipe' => 'kuliner', 'lokasi', 'judul', 'deskripsi' => '', 'gambar' => null, 'tombolTeks' => 'Detail', 'peringkat' => null])
@php
    // ikon badge per jenis konten / badge icon per content type
    $ikon = match ($tipe) {
        'budaya' => 'fa-masks-theater',
        'destinasi' => 'fa-mountain-sun',
        default => 'fa-bowl-food',
    };
@endphp
<div class="discovery-card-modern h-100">
    <div class="discovery-img-wrapper">
        <span @class(['card-category-tag', 'tag-budaya' => $tipe === 'budaya'])><i class="fa-solid {{ $ikon }} me-1"></i> {{ $badge }}</span>
        <x-gambar :src="$gambar" :alt="$judul" />
        <x-lencana-peringkat :peringkat="$peringkat" class="badge-peringkat--sudut" />
    </div>
    <div class="p-4 d-flex flex-column flex-grow-1">
        <div class="d-flex justify-content-between align-items-center small text-muted mb-2">
            <span><i class="fa-solid fa-location-dot me-1"></i> {{ $lokasi }}</span>
        </div>
        <h5 class="mb-2"><a href="{{ $href }}" class="card-title-link">{{ $judul }}</a></h5>
        <p class="small text-muted mb-4 flex-grow-1">{{ $deskripsi }}</p>
        <a href="{{ $href }}" class="btn btn-sm btn-aceh-outline w-100">{{ $tombolTeks }}</a>
    </div>
</div>
