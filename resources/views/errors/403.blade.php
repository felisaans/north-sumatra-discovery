@extends('layouts.app')
@section('judul', 'Akses Ditolak — North Sumatra Discovery')
@section('footer_ringkas')@endsection

@section('isi')
{{-- Tamu yang belum login tidak sampai sini (middleware auth mengarahkan ke /masuk); ini untuk pengguna biasa. --}}
<x-halaman-galat ikon="fa-lock" judul="Akses Ditolak" teks="Halaman ini hanya untuk admin. Silakan masuk dengan akun admin terlebih dahulu.">
    <a href="{{ route('beranda') }}" class="btn btn-primary-custom px-4">Kembali ke Beranda</a>
</x-halaman-galat>
@endsection
