{{-- <x-video-lazy :item="config('video.hero')" mode="eager" label="..." />
     Komponen video beranda (hero + 4 editorial). Poster kosong -> pakai gambar 'latar' item. Sumber video = URL online (config/video.php <- .env).
     Kalau URL video kosong -> tampil gambar poster saja (atau kosong bila poster juga kosong).

     mode="eager" -> hero: langsung di-load saat halaman dibuka (sudah di atas fold)
     mode="lazy"  -> editorial: src KOSONG di HTML; baru diisi dari data-src oleh video-lazy.js
                     saat mendekati viewport (jadi browser tidak melakukan request apa pun sebelumnya)
--}}
@props(['item', 'mode' => 'lazy', 'label' => ''])
@php
    $eager = $mode === 'eager';
    $urlVideo = \App\Support\Media::url($item['video'] ?? null);
    $urlPoster = \App\Support\Media::url($item['poster'] ?? ($item['latar']['url'] ?? null));
    $ytId = \App\Support\Media::youtubeId($item['youtube'] ?? null);
@endphp
@if ($ytId)
    {{-- YouTube: senyap, berulang pada potongan mulai..selesai (diatur youtube-bg.js; frame induk harus position:relative) --}}
    <div class="yt-bg" role="img" aria-label="{{ $label }}" data-yt-bg data-yt-id="{{ $ytId }}" data-yt-start="{{ $item['mulai'] ?? 0 }}" data-yt-end="{{ $item['selesai'] ?? 30 }}">
        <div class="yt-bg__frame"><div class="yt-bg__player"></div></div>
    </div>
@elseif (! $urlVideo)
    @if ($urlPoster)
        <img src="{{ $urlPoster }}" onerror="this.remove();" alt="{{ $label }}" width="{{ $item['lebar'] }}" height="{{ $item['tinggi'] }}" decoding="async" referrerpolicy="no-referrer" {{ $attributes }}>
    @endif
@else
<video
    muted playsinline loop disablepictureinpicture disableremoteplayback
    @if ($eager) autoplay preload="auto" @else preload="none" @endif
    @if ($urlPoster) poster="{{ $urlPoster }}" @endif
    width="{{ $item['lebar'] }}" height="{{ $item['tinggi'] }}"
    role="img" aria-label="{{ $label }}"
    data-video data-video-mode="{{ $mode }}" data-src="{{ $urlVideo }}"
    {{ $attributes }}></video>
@endif
