{{-- <x-gambar :src="$k->gambar_url" :alt="$k->nama" kelas="editorial-img" fallback="https://placehold.co/600x400/eee/333" />
     Pengganti imgUrl() + onerror di JS lama: gambar kosong -> placeholder; gambar gagal dimuat -> placeholder bertuliskan nama. --}}
@props(['src' => null, 'alt' => '', 'kelas' => '', 'fallback' => 'https://placehold.co/600x400/eee/333', 'lazy' => true])
<img src="{{ $src ?: 'https://placehold.co/600x600/F8E8D0/54205F?text=North+Sumatra+Discovery' }}"
     alt="{{ $alt }}"
     @if ($kelas) class="{{ $kelas }}" @endif
     @if ($lazy) loading="lazy" @endif
     onerror="this.onerror=null;this.src='{{ $fallback }}?text={{ rawurlencode($alt) }}'"
     {{ $attributes }}>
