{{-- <x-gambar-editorial :item="config('video.editorial.sabang.latar')" alt="..." />
     Pengganti video di frame Sorotan Editorial: gambar yang sama dengan latar kartu (URL online / hotlink).
     Gagal dimuat -> elemen dihapus, frame tetap tampil sebagai blok warna (tidak ada ikon rusak). --}}
@props(['item', 'alt' => '', 'lebar' => null, 'tinggi' => null])
<img src="{{ $item['url'] }}" alt="{{ $alt }}"
     @if ($lebar && $tinggi) width="{{ $lebar }}" height="{{ $tinggi }}" @endif
     loading="lazy" decoding="async" referrerpolicy="no-referrer"
     onerror="this.remove();">
