@extends('layouts.app')
@section('judul', 'Favorit Saya — North Sumatra Discovery')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/kuliner.css') }}">
@endpush

@section('isi')
    <header class="hero-banner">
        <div class="container">
            <div class="hero-tag-pill">
                <span class="tag-dot"></span>
                <span>Koleksi Pribadi</span>
            </div>
            <h1>Kuliner <em>Favorit</em> Saya.</h1>
            <p>Hidangan yang kamu simpan dari halaman detail kuliner. Klik tombol hati lagi di sana untuk melepasnya.</p>
        </div>
    </header>

    <div class="container pb-5 mt-5">
        @forelse ($kuliner as $k)
            <x-row-kuliner :kuliner="$k" :i="$loop->index" />
        @empty
            <x-pesan-kosong>
                Belum ada favorit. <a href="{{ route('kuliner.index') }}">Jelajahi kuliner</a> lalu simpan yang kamu suka.
            </x-pesan-kosong>
        @endforelse
    </div>
@endsection
