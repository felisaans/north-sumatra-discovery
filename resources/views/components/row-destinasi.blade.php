{{-- Baris Destinasi: layout overlapping. Pengganti rowDestinasi() --}}
@props(['destinasi', 'i' => 0])
<div @class(['dest-row', 'reverse' => $i % 2 === 1]) data-daerah="{{ $destinasi->daerah?->slug }}">
    <div class="dest-image-wrapper">
        @if ($destinasi->daerah?->nama_daerah)
            <span class="dest-region-tag"><i class="fa-solid fa-location-dot me-1"></i>{{ $destinasi->daerah->nama_daerah }}</span>
        @endif
        <x-gambar :src="$destinasi->gambar_url" :alt="$destinasi->nama" />
    </div>
    <div class="dest-content-box">
        <div class="dest-title-pill">{{ $destinasi->nama }}</div>
        <p class="dest-desc">{{ $destinasi->deskripsi }}</p>
        @php $adaMaps = preg_match('#^https?://#i', (string) $destinasi->maps_url) === 1; @endphp
        @if (filled($destinasi->jenis_wisata) || $adaMaps)
            <div class="dest-footer-meta">
                @if (filled($destinasi->jenis_wisata))
                    <span class="text-muted fw-medium small"><i class="fa-solid fa-map text-teal me-2"></i>{{ $destinasi->jenis_wisata }}</span>
                @endif
                {{-- Link diisi admin (Dashboard -> Destinasi Wisata -> Link Google Maps) --}}
                @if ($adaMaps)
                    <a href="{{ $destinasi->maps_url }}" class="dest-maps-link" target="_blank" rel="noopener noreferrer">
                        <i class="fa-solid fa-location-arrow" aria-hidden="true"></i> Lihat di Maps
                        <span class="visually-hidden">(membuka tab baru)</span>
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
