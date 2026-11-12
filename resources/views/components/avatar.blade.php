{{-- <x-avatar :nama="$p->nama" :email="$p->email" :ukuran="52" kelas="experience-avatar" />
     Gravatar dari email (hash MD5 dihitung di PHP). Tanpa email -> lingkaran inisial.
     Bila Gravatar tidak punya foto (404), avatar.js menggantinya dengan lingkaran inisial. --}}
@props(['nama' => '', 'email' => null, 'ukuran' => 40, 'kelas' => ''])
@php
    $inisial = collect(preg_split('/\s+/', trim((string) $nama)) ?: [])
        ->filter()->take(2)
        ->map(fn ($kata) => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($kata, 0, 1)))
        ->implode('') ?: '?';
    $gaya = 'width:'.$ukuran.'px;height:'.$ukuran.'px;font-size:'.round($ukuran * 0.38).'px';
@endphp
@if ($email)
    <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($email))) }}?d=404&amp;s={{ $ukuran * 2 }}"
         alt="{{ $nama }}" class="{{ $kelas }}"
         style="width:{{ $ukuran }}px;height:{{ $ukuran }}px;object-fit:cover"
         data-inisial="{{ $inisial }}" data-avatar-class="{{ $kelas }}" data-avatar-size="{{ $ukuran }}">
@else
    <div class="avatar-initial {{ $kelas }}" style="{{ $gaya }}">{{ $inisial }}</div>
@endif
