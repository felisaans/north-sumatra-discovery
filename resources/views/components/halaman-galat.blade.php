{{-- Kerangka halaman error: <x-halaman-galat ikon="fa-lock" judul="Akses Ditolak" teks="...">tombol-tombol</x-halaman-galat> --}}
@props(['ikon' => 'fa-compass', 'judul', 'teks'])
<div class="container page-offset pb-5 text-center">
    <i class="fa-solid {{ $ikon }} fs-1 text-muted mb-3 d-block"></i>
    <h1 class="h3 fw-bold">{{ $judul }}</h1>
    <p class="text-muted">{{ $teks }}</p>
    <div class="d-flex flex-wrap justify-content-center gap-2">{{ $slot }}</div>
</div>
