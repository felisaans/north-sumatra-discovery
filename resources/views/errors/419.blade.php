@extends('layouts.app')
@section('judul', 'Sesi Habis — North Sumatra Discovery')
@section('footer_ringkas')@endsection

@section('isi')
{{-- 419 = token CSRF kedaluwarsa (halaman terlalu lama terbuka). Cukup kembali dan kirim ulang. --}}
<x-halaman-galat ikon="fa-hourglass-end" judul="Sesi halaman sudah habis" teks="Halaman ini terlalu lama terbuka sehingga formulirnya kedaluwarsa. Kembali, muat ulang halaman, lalu kirim lagi.">
    <a href="{{ url()->previous(route('beranda')) }}" class="btn btn-aceh-outline px-4">Kembali</a>
    <a href="{{ route('beranda') }}" class="btn btn-primary-custom px-4">Ke Beranda</a>
</x-halaman-galat>
@endsection
