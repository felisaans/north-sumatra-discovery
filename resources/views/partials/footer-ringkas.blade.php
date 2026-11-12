{{-- Footer satu baris (detail kuliner & halaman masuk). Teks tambahan lewat @section('footer_teks') --}}
<footer class="aceh-footer">
    <div class="container">
        <p class="mb-0 text-center small opacity-75">&copy; {{ date('Y') }} Bit2Bit. RPL Studio.@hasSection('footer_teks') @yield('footer_teks')@endif</p>
    </div>
</footer>
