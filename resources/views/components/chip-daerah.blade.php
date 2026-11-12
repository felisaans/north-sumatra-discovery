{{-- Chip filter daerah. Dengan `href` -> <a> (Destinasi, jalan tanpa JS). Tanpa `href` -> <button> (Beranda, disaring di klien). --}}
@props(['slug', 'teks', 'aktif' => false, 'href' => null])
@if ($href)
    <a href="{{ $href }}" @class(['region-chip', 'active' => $aktif]) data-filter-daerah="{{ $slug }}" style="text-decoration:none">{{ $teks }}</a>
@else
    <button type="button" @class(['region-chip', 'active' => $aktif]) data-filter-daerah="{{ $slug }}">{{ $teks }}</button>
@endif
