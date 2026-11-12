@forelse ($budaya as $b)
    <x-row-budaya :budaya="$b" :i="$loop->index" />
@empty
    <x-pesan-kosong>Tidak ada budaya/tradisi yang cocok dengan pencarianmu.</x-pesan-kosong>
@endforelse
