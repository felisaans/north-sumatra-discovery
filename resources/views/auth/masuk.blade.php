@extends('layouts.app')
@section('judul', 'Masuk - North Sumatra Discovery')
@section('footer_ringkas')@endsection
@section('footer_teks', 'Platform Eksplorasi Kuliner dan Budaya Lokal berbasis Web.')

@php
    // Setelah gagal daftar, tab Daftar yang dibuka; selain itu tab Masuk
    $tab = $errors->hasBag('daftar') && $errors->daftar->any() ? 'daftar' : 'masuk';
@endphp

@section('isi')
<main class="container py-5" style="max-width: 460px; margin-top: 70px;">
    <div class="filter-section">
        <h1 class="h3 fw-bold text-center mb-1 font-editorial text-teal">Selamat Datang</h1>
        <p class="text-muted text-center small mb-4">Masuk untuk memberi ulasan dan menyimpan favorit.</p>

        <ul class="nav nav-pills nav-fill mb-4 gap-2" id="tabAuth">
            <li class="nav-item"><button type="button" @class(['nav-link', 'active' => $tab === 'masuk']) data-mode="masuk">Masuk</button></li>
            <li class="nav-item"><button type="button" @class(['nav-link', 'active' => $tab === 'daftar']) data-mode="daftar">Daftar</button></li>
        </ul>

        {{-- Form MASUK --}}
        <form id="formMasuk" method="POST" action="{{ route('masuk.proses') }}" data-panel="masuk" @class(['d-none' => $tab !== 'masuk'])>
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold" for="mEmail">Email</label>
                <input id="mEmail" name="email" type="email" class="form-control" autocomplete="email" maxlength="150"
                       value="{{ $errors->hasBag('masuk') ? old('email') : '' }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold" for="mSandi">Kata sandi</label>
                <input id="mSandi" name="password" type="password" class="form-control" autocomplete="current-password" required>
            </div>
            <div class="small mb-3 text-danger" role="alert">
                @foreach ($errors->masuk->all() as $galat)<div>{{ $galat }}</div>@endforeach
            </div>
            <button class="btn btn-primary-custom w-100" type="submit">Masuk</button>
        </form>

        {{-- Form DAFTAR --}}
        <form id="formDaftar" method="POST" action="{{ route('daftar') }}" data-panel="daftar" @class(['d-none' => $tab !== 'daftar'])>
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold" for="dNama">Nama</label>
                <input id="dNama" name="name" class="form-control" autocomplete="name" maxlength="100"
                       value="{{ $errors->hasBag('daftar') ? old('name') : '' }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold" for="dEmail">Email</label>
                <input id="dEmail" name="email" type="email" class="form-control" autocomplete="email" maxlength="150"
                       value="{{ $errors->hasBag('daftar') ? old('email') : '' }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold" for="dSandi">Kata sandi</label>
                <input id="dSandi" name="password" type="password" class="form-control" minlength="6" autocomplete="new-password" required>
            </div>
            <div class="small mb-3 text-danger" role="alert">
                @foreach ($errors->daftar->all() as $galat)<div>{{ $galat }}</div>@endforeach
            </div>
            <button class="btn btn-primary-custom w-100" type="submit">Daftar</button>
        </form>
    </div>
</main>
@endsection

@push('js')
    <script src="{{ asset('assets/js/auth-tab.js') }}" defer></script>
@endpush
