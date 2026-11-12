{{-- Status "gagal" (F20) --}}
<p {{ $attributes->merge(['class' => 'text-center text-danger py-5']) }}>{{ $slot->isEmpty() ? 'Gagal memuat data. Coba muat ulang halaman.' : $slot }}</p>
