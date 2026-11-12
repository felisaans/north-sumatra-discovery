{{-- Gambar latar kartu Sorotan Editorial — dipakai dari URL online (hotlink), bukan file project.
     Background image for an editorial card, hotlinked from an online URL.

     - loading="lazy"        : gambar hanya diunduh saat kartu mendekati layar (hemat bandwidth)
     - referrerpolicy        : tanpa Referer, supaya lolos dari sebagian proteksi hotlink
     - onload                : memasang class "latar-aktif" di kartu -> baru saat itu warna teks berganti
     - onerror               : kartu kembali ke desain putih semula (tidak ada elemen rusak)
--}}
@props(['item'])
@if (! empty($item['url']))
<div class="featured-bg" aria-hidden="true">
    <img src="{{ $item['url'] }}" alt="" loading="lazy" decoding="async" referrerpolicy="no-referrer"
         onload="this.classList.add('is-loaded'); this.closest('.featured-block')?.classList.add('latar-aktif');"
         onerror="this.parentElement.remove();">
</div>
@endif
