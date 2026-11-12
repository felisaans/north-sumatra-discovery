{{-- Footer lengkap. Kolom ke-3 bisa diganti per halaman lewat @section('footer_kolom') --}}
<footer class="aceh-footer">
    <div class="container">
        <div class="row g-5 mb-5">
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <x-logo-ns :ukuran="40" />
                    <span class="brand-text text-white">North Sumatra Discovery</span>
                </div>
                <p class="small opacity-75 mb-0">
                    Horas! Tempat mengenal yang dimakan, ditarikan, dan dijaga di Sumatera Utara, dari kota sampai kampung.
                </p>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="footer-heading">Jalan-jalan</h6>
                <ul class="list-unstyled footer-links mb-0">
                    <li><a href="{{ route('beranda') }}">Beranda</a></li>
                    <li><a href="{{ route('kuliner.index') }}">Kuliner</a></li>
                    <li><a href="{{ route('budaya.index') }}">Budaya &amp; Tradisi</a></li>
                    <li><a href="{{ route('destinasi.index') }}">Tempat Singgah</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-3">
                @hasSection('footer_kolom')
                    @yield('footer_kolom')
                @else
                    <h6 class="footer-heading">Akunmu</h6>
                    <ul class="list-unstyled footer-links mb-0">
                        @guest
                            <li><a href="{{ route('masuk') }}">Masuk atau daftar</a></li>
                        @endguest
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                    </ul>
                @endif
            </div>
            @php
                // Isian .env boleh URL penuh atau nama akun (@akun) -> diubah jadi URL lengkap; kosong = disembunyikan
                $sosial = collect(config('situs.sosial'))
                    ->map(fn ($m, $platform) => [...$m, 'url' => \App\Support\Sosial::url($platform, $m['url'])])
                    ->filter(fn ($m) => filled($m['url']));
            @endphp
            @if ($sosial->isNotEmpty())
                <div class="col-lg-3">
                    <h6 class="footer-heading">Ikuti Kami</h6>
                    <div class="d-flex gap-3 fs-5">
                        @foreach ($sosial as $m)
                            <a href="{{ $m['url'] }}" class="text-white opacity-75" aria-label="{{ $m['nama'] }}" target="_blank" rel="noopener noreferrer"><i class="fa-brands {{ $m['ikon'] }}"></i></a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        <div class="pt-4 text-center small opacity-50">
            &copy; {{ date('Y') }} Bit2Bit. RPL Studio.
        </div>
    </div>
</footer>
