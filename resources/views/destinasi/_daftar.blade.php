@forelse ($destinasi as $d)
    <x-row-destinasi :destinasi="$d" :i="$loop->index" />
@empty
    <x-pesan-kosong>Belum ada destinasi untuk daerah ini.</x-pesan-kosong>
@endforelse
