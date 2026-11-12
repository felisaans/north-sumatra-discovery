{{-- <x-bintang :n="4" /> -> ★★★★☆ (pengganti bintang() di ui.js) --}}
@props(['n' => 0])
{{ str_repeat('★', max(0, min(5, (int) $n))) }}{{ str_repeat('☆', 5 - max(0, min(5, (int) $n))) }}
