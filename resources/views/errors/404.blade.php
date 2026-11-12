@extends('layouts.app')
@section('judul', 'Halaman Tidak Ditemukan — North Sumatra Discovery')
@section('footer_ringkas')@endsection

@section('isi')
<x-halaman-galat ikon="fa-compass" judul="Halaman tidak ditemukan" teks="Halaman atau data yang kamu cari tidak ada atau sudah dihapus.">
    <a href="{{ route('kuliner.index') }}" class="btn btn-aceh-outline px-4">Lihat Daftar Kuliner</a>
    <a href="{{ route('beranda') }}" class="btn btn-primary-custom px-4">Kembali ke Beranda</a>
</x-halaman-galat>
@endsection
