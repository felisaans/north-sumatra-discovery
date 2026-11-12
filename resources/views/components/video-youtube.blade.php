{{-- Background video YouTube untuk header halaman (muted, loop potongan, tanpa kontrol).
     Taruh sebagai ANAK PERTAMA di <header>/<section> yang diberi class "has-video":

       <header class="hero-banner has-video">
           <x-video-youtube :item="config('video.header.kuliner')" />
           <div class="container"> ... </div>
       </header>

     Pemutaran diatur youtube-bg.js. Sebelum video benar-benar jalan (atau jika gagal / reduced-motion /
     hemat data) header tetap tampil seperti desain lama, jadi tidak ada yang rusak.
     Playback is handled by youtube-bg.js; the original header design stays as the fallback. --}}
@props(['item'])
@php
    $ttId = \App\Support\Media::tiktokId($item['tiktok'] ?? null);
    $ytId = \App\Support\Media::youtubeId($item['youtube'] ?? null);
@endphp
@if ($ttId)
{{-- TikTok (header Kuliner): iframe player resmi TikTok, diatur tiktok-bg.js. Dibuat oleh JS (src kosong di HTML). --}}
<div class="yt-bg yt-bg--tiktok" aria-hidden="true" data-tt-bg
     data-tt-id="{{ $ttId }}" data-tt-start="{{ $item['mulai'] }}" data-tt-end="{{ $item['selesai'] }}">
    <div class="yt-bg__frame"></div>
    <div class="yt-bg__overlay"></div>
</div>
@elseif ($ytId)
<div class="yt-bg" aria-hidden="true" data-yt-bg
     data-yt-id="{{ $ytId }}" data-yt-start="{{ $item['mulai'] }}" data-yt-end="{{ $item['selesai'] }}">
    <div class="yt-bg__frame"><div class="yt-bg__player"></div></div>
    <div class="yt-bg__overlay"></div>
</div>
@endif
