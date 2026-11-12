{{-- Potongan daftar Kuliner: dipakai halaman penuh DAN balasan live search (?partial=1) --}}
@forelse ($kuliner as $k)
    <x-row-kuliner :kuliner="$k" :i="$loop->index" />
@empty
    <x-pesan-kosong>Tidak ada kuliner yang cocok dengan pencarianmu.</x-pesan-kosong>
@endforelse
