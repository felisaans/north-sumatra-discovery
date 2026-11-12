{{-- Satu slide carousel Pengalaman Pengunjung. Dipakai Beranda dan balasan JSON PengalamanController (slide baru).
     Teks selalu putih di atas gradasi gelap di bagian bawah foto (.experience-caption); kelas is-dark/is-light dari luminance.js tidak lagi mengubah warna teks. --}}
@props(['p', 'aktif' => false])
@php
    // Format teks tersimpan: "📍 destinasi — ★★★☆☆" + baris kosong + cerita. Dipecah agar bintang bisa diberi warna kuning.
    $bagian = preg_split('/\R{2,}/u', trim($p->teks), 2);
    $adaKepala = count($bagian) === 2 && str_starts_with($bagian[0], '📍');
    $cerita = $adaKepala ? $bagian[1] : trim($p->teks);
    $tempat = $bintangPenuh = $bintangKosong = '';
    if ($adaKepala) {
        if (preg_match('/^(.*?)\s*(★*)(☆*)\s*$/u', $bagian[0], $m)) {
            [$tempat, $bintangPenuh, $bintangKosong] = [rtrim($m[1], " \t—-"), $m[2], $m[3]];
        } else {
            $tempat = $bagian[0];
        }
    }
@endphp
<div @class(['carousel-item', 'active' => $aktif])>
    <div class="experience-card is-dark" data-foto="{{ $p->foto_url }}" style="background-image: url('{{ $p->foto_url }}');">
        <div class="experience-overlay"></div>
        <div class="experience-header">
            <span class="experience-tag"><i class="fa-solid fa-location-dot me-1"></i> {{ $p->daerah?->nama_daerah ?: 'Sumatera Utara' }}</span>
        </div>
        <div class="experience-body">
            <div class="experience-caption">
                @if ($adaKepala)
                    <p class="experience-place"><span>{{ $tempat }}</span>@if ($bintangPenuh !== '' || $bintangKosong !== '') <span class="experience-stars" aria-label="Rating {{ mb_strlen($bintangPenuh) }} dari 5">{{ $bintangPenuh }}<span class="experience-stars-kosong">{{ $bintangKosong }}</span></span>@endif</p>
                @endif
                <p class="experience-quote">{{ $cerita }}</p>
                <div class="experience-footer">
                    <div class="experience-user">
                        <x-avatar :nama="$p->nama" :email="$p->email" :ukuran="52" kelas="experience-avatar" />
                        <div>
                            <h6 class="experience-user-name mb-0">{{ $p->nama }}</h6>
                            <p class="experience-user-role m-0">Wisatawan</p>
                        </div>
                    </div>
                    {{-- Suka (like). Tamu boleh melihat jumlahnya; menekan tombol mengarahkan ke halaman masuk (suka-pengalaman.js) --}}
                    @php $disuka = (bool) ($p->disuka_saya ?? false); @endphp
                    <button type="button" @class(['experience-like', 'is-liked' => $disuka])
                            data-suka-url="{{ route('pengalaman.suka', $p) }}" data-masuk-url="{{ route('masuk') }}"
                            aria-pressed="{{ $disuka ? 'true' : 'false' }}" aria-label="Suka pengalaman ini">
                        <i class="{{ $disuka ? 'fa-solid' : 'fa-regular' }} fa-heart" aria-hidden="true"></i>
                        <span class="experience-like-jumlah">{{ (int) ($p->suka_count ?? 0) }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
