{{-- Logo North Sumatra Discovery: atap pelana Rumah Bolon di atas garis air. Satu sumber untuk navbar, footer, admin.
     <x-logo-ns /> (bulat, warna mengikuti tema)  |  <x-logo-ns :ukuran="40" /> --}}
@props(['ukuran' => 34])
<span {{ $attributes->class(['brand-icon']) }} style="--brand-ukuran: {{ $ukuran }}px" aria-hidden="true">
    <svg class="brand-logo" viewBox="0 0 64 64" width="{{ round($ukuran * 0.68) }}" height="{{ round($ukuran * 0.68) }}" fill="currentColor" focusable="false"><path d="M8 38C16 34 17 24 20 13c5 8 8 11 12 11s7-3 12-11c3 11 4 21 12 25z"/><path d="M8 48c6-3.5 12 3.5 24 0s18 3.5 24 0" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round"/></svg>
</span>
