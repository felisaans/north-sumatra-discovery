@extends('layouts.app')
@section('judul', 'Terlalu Banyak Permintaan — North Sumatra Discovery')
@section('footer_ringkas')@endsection

@section('isi')
<x-halaman-galat ikon="fa-gauge-high" judul="Terlalu banyak permintaan" teks="Kamu mengirim terlalu cepat. Tunggu sebentar lalu coba lagi.">
    <a href="{{ route('beranda') }}" class="btn btn-primary-custom px-4">Kembali ke Beranda</a>
</x-halaman-galat>
@endsection
