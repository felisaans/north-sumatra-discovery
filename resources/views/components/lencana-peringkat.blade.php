{{-- Lencana peringkat Top 10 kuliner (bintang + favorit pengguna). Tidak tampil bila kuliner di luar Top 10.
     #1 emas terang, #2 perak, #3 perunggu, #4-#10 kaca gelap. Aturan hitung: App\Support\PeringkatKuliner --}}
@props(['peringkat' => null])
@if ($peringkat)
    <span {{ $attributes->class(['badge-peringkat', 'peringkat-'.($peringkat <= 3 ? $peringkat : 'lain')]) }}
          title="Peringkat {{ $peringkat }} dari 10 kuliner pilihan pengunjung (bintang & favorit)"
          aria-label="Peringkat {{ $peringkat }} kuliner pilihan pengunjung">#{{ $peringkat }}</span>
@endif
