@extends('layouts.app')

@section('judul', 'North Sumatra Discovery — Jelajahi Kuliner, Budaya & Tradisi')



@push('css')

    <link rel="stylesheet" href="{{ asset('assets/css/header-video.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/beranda.css') }}">

    {{-- Hero: video & poster di-host online (lihat config/video.php, .env HERO_VIDEO_URL / HERO_POSTER_URL) --}}

    @if ($heroPoster = \App\Support\Media::url(config('video.hero.poster')))

        <link rel="preload" as="image" href="{{ $heroPoster }}" fetchpriority="high">

    @endif

@endpush



@push('js')

    <script src="{{ asset('assets/js/video-lazy.js') }}" defer></script>

    <script src="{{ asset('assets/js/dua-sisi.js') }}" defer></script>

    <script src="{{ asset('assets/js/youtube-bg.js') }}" defer></script>

@endpush



@section('isi')

    <!-- HERO -->

    {{-- Latar hero = gambar poster hero (HERO_POSTER_URL) + tint tipis (lihat .hero-editorial--foto di beranda.css) --}}

    @php $latarHero = \App\Support\Media::url(config('video.hero.poster')); @endphp

    <section class="hero-editorial {{ $latarHero ? 'hero-editorial--foto' : '' }}"

        @if ($latarHero) style="--hero-foto: url('{{ $latarHero }}')" @endif>

        <div class="container">

            <div class="row align-items-center g-5">

                <div class="col-lg-6">

                    <div class="hero-tag-pill">

                        <span class="tag-dot"></span>

                        <span>Horas! Yuk, kenali Sumatera Utara</span>

                    </div>

                    <h1 class="hero-heading">

                        Dari sudut kota Medan hingga pemandangan Danau Toba.

                    </h1>

                    <p class="hero-description">

                        Temukan makanan khas, kenali tradisi lokal, dan cari tempat menarik untuk dikunjungi. Ada banyak sisi Sumatera Utara yang bisa kamu jelajahi.

                    </p>

                    {{-- Mencari di kuliner + budaya + destinasi sekaligus (route cari.index) --}}

                    <form action="{{ route('cari.index') }}" method="GET" class="search-box-container mb-4" role="search">

                        <div class="input-group search-box-pill">

                            <span class="input-group-text bg-transparent border-0 text-teal ps-2">

                                <i class="fa-solid fa-magnifying-glass"></i>

                            </span>

                            <input type="search" name="q" class="form-control" maxlength="100" autocomplete="off" aria-label="Kata kunci pencarian" placeholder="Cari makanan khas, tarian, rumah adat, atau destinasi...">

                            <button type="submit" class="btn btn-aceh-primary">Cari</button>

                        </div>

                    </form>

                    <div class="d-flex align-items-center gap-4 pt-2">

                        <div>

                            <div class="h4 font-editorial fw-bold m-0 text-teal">{{ $daerahChip->count() }}</div>

                            <small class="text-muted">Daerah</small>

                        </div>

                        <div class="vr opacity-25" style="height: 30px;"></div>

                        <div>

                            <div class="h4 font-editorial fw-bold m-0 text-teal">{{ $jumlahKuliner }}</div>

                            <small class="text-muted">Kuliner</small>

                        </div>

                        <div class="vr opacity-25" style="height: 30px;"></div>

                        <div>

                            <div class="h4 font-editorial fw-bold m-0 text-teal">{{ $jumlahBudaya }}</div>

                            <small class="text-muted">Budaya & tradisi</small>

                        </div>

                    </div>

                </div>

                <div class="col-lg-6">

                    {{-- Slider kota <-> kampung (dua sisi Sumatera Utara). Bila HERO_VIDEO_URL / HERO_POSTER_URL diisi di .env, yang tampil video/foto itu. --}}

                    @if (config('video.hero.video') || $latarHero)

                        <div class="ds ds--media"><x-video-lazy :item="config('video.hero')" mode="eager" label="Suasana Sumatera Utara" /></div>

                    @else

                        @php
                            $fotoKota = \App\Support\Media::url(config('video.dua_sisi.kota'));
                            $fotoKampung = \App\Support\Media::url(config('video.dua_sisi.kampung'));
                        @endphp

                        <div class="ds {{ ($fotoKota || $fotoKampung) ? 'ds--foto' : '' }}" id="duaSisi" role="group" aria-label="Sisi kota dan sisi kampung Sumatera Utara">

                            <div class="ds-layer" data-scene="city" data-seed="7">

                                @if ($fotoKota)<img class="ds-foto" src="{{ $fotoKota }}" alt="Suasana kota di Sumatera Utara" width="800" height="1000" decoding="async" referrerpolicy="no-referrer" draggable="false" onerror="this.remove();">@endif

                                <div class="ds-lab"><b>Kota</b><span>Kedai kopi, bangunan bersejarah, dan aktivitas kota.</span></div>

                            </div>

                            <div class="ds-layer" id="dsKampung" data-scene="lake" data-seed="3">

                                @if ($fotoKampung)<img class="ds-foto" src="{{ $fotoKampung }}" alt="Suasana kampung di Sumatera Utara" width="800" height="1000" decoding="async" referrerpolicy="no-referrer" draggable="false" onerror="this.remove();">@endif

                                <div class="ds-lab ds-r"><b>Kampung</b><span>Pemandangan danau, rumah adat, dan kehidupan yang lebih santai.</span></div>

                            </div>

                            <div class="ds-grip" id="dsGrip" role="slider" tabindex="0" aria-label="Geser antara kota dan kampung" aria-valuemin="0" aria-valuemax="100" aria-valuenow="50">

                                <i><svg viewBox="0 0 24 24" fill="none" stroke="#29212B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 7l-5 5 5 5M15 7l5 5-5 5"/></svg></i>

                            </div>

                            <p class="ds-hint">Geser untuk melihat sisi lainnya</p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </section>





    <!-- CERITA & BERITA TERBARU (tabel berita: 8 terbaru) — marquee otomatis ke kiri, looping, CSS-only -->

    <section class="section-berita">

        <div class="container">

            <div class="d-flex align-items-center justify-content-between mb-4">

                <div>

                    <span class="text-uppercase tracking-wider text-teal fw-bold small">Kabar terbaru</span>

                    <h3 class="fw-bold font-editorial m-0 mt-1">Cerita terbaru dari Sumatera Utara</h3>

                </div>

            </div>

        </div>



        @if ($berita->isEmpty())

            <div class="container">

                <x-pesan-kosong class="py-4 w-100">Belum ada cerita terbaru. Kunjungi lagi nanti untuk membaca kabar dan cerita baru.</x-pesan-kosong>

            </div>

        @else

            @php

                // Satu "grup" harus cukup lebar menutupi layar supaya loop mulus -> minimal 6 kartu per grup.

                // One group must be wider than the viewport so the loop has no gap -> at least 6 cards per group.

                $ulang = (int) ceil(6 / $berita->count());

                $kartuPerGrup = $ulang * $berita->count();

            @endphp

            <div class="berita-marquee" role="region" aria-label="Cerita dan berita terbaru">

                {{-- --n = jumlah kartu per grup -> durasi ikut jumlah kartu, jadi kecepatan selalu konstan --}}

                <div class="berita-track" style="--n: {{ $kartuPerGrup }};">

                    @foreach ([false, true] as $klon)

                        {{-- grup ke-2 = salinan untuk looping; disembunyikan dari screen reader --}}

                        <div class="berita-group" @if ($klon) aria-hidden="true" data-klon @endif>

                            @for ($r = 0; $r < $ulang; $r++)

                                @foreach ($berita as $n)

                                    <div class="berita-item">

                                        <div class="news-ticker-card p-4 h-100">

                                            <span class="badge-berita mb-2">{{ $n->label ?: 'Cerita' }}</span>

                                            <h5 class="fw-bold mb-2 font-editorial">{{ $n->judul }}</h5>

                                            <p class="text-muted small mb-3">{{ $n->ringkasan }}</p>

                                            <span class="text-secondary small"><i class="fa-regular fa-clock me-1"></i> {{ $n->tanggal->translatedFormat('j F Y') }}</span>

                                        </div>

                                    </div>

                                @endforeach

                            @endfor

                        </div>

                    @endforeach

                </div>

            </div>

        @endif

    </section>



    <!-- FEATURED (Kenali Sumatera Utara) — 4 video online, lazy-load lewat video-lazy.js (gambar latar = poster/cadangan) -->

    <section class="section-featured-stories">

        <div class="container">

            <div class="row justify-content-center text-center mb-5">

                <div class="col-lg-7">

                    <span class="text-uppercase tracking-wider text-teal fw-bold small">Kenali Sumatera Utara</span>

                    <h2 class="font-editorial display-6 fw-bold mt-2">Empat hal menarik dari Sumatera Utara</h2>

                    <p class="text-muted">Kenali beberapa hal yang lekat dengan Sumatera Utara, mulai dari kesenian tradisional sampai makanan khasnya.</p>

                </div>

            </div>



            </div>



            {{-- 1. TOR-TOR — landscape 16:9 (kiri) --}}

            <div class="featured-block tema-{{ config('video.editorial.tor-tor.latar.tema') }}" data-sisi-teks="kanan">

                <x-latar-editorial :item="config('video.editorial.tor-tor.latar')" />

                <div class="container">

                <div class="row align-items-center g-4">

                    <div class="col-lg-6">

                        <div class="featured-video-frame" style="--vid-w: {{ config('video.editorial.tor-tor.lebar') }}; --vid-h: {{ config('video.editorial.tor-tor.tinggi') }};">

                            <span class="badge-featured-category accent-sky">Tarian tradisional</span>

                            <div class="scene-ph" data-scene="lake" data-seed="3" aria-hidden="true"></div>

                            <x-video-lazy :item="config('video.editorial.tor-tor')" label="Penari tor-tor bergerak mengikuti irama gondang" />

                        </div>

                    </div>

                    <div class="col-lg-6 ps-lg-5">

                        <div class="featured-meta">

                            <span><i class="fa-solid fa-masks-theater text-teal"></i> Samosir</span>

                            <span><i class="fa-solid fa-drum text-teal"></i> Tarian adat</span>

                        </div>

                        <h3 class="featured-title">Tor-tor: gerak tari dalam tradisi Batak</h3>

                        <p class="featured-excerpt">

                            Tor-tor biasanya diiringi musik gondang dan hadir dalam berbagai upacara adat Batak. Tarian ini menjadi bagian dari rangkaian acara, termasuk untuk menyampaikan penghormatan dan rasa syukur.

                        </p>

                        <a href="{{ route('budaya.index', ['q' => 'tor-tor']) }}" class="btn btn-aceh-glass">

                            Pelajari tentang tor-tor <i class="fa-solid fa-arrow-right ms-2"></i>

                        </a>

                    </div>

                </div>

                </div>

            </div>



            {{-- 2. ARSIK IKAN MAS — portrait 9:16 (kanan) --}}

            <div class="featured-block tema-{{ config('video.editorial.arsik-ikan-mas.latar.tema') }}" data-sisi-teks="kiri">

                <x-latar-editorial :item="config('video.editorial.arsik-ikan-mas.latar')" />

                <div class="container">

                <div class="row align-items-center g-4 flex-lg-row-reverse">

                    <div class="col-lg-6">

                        <div class="featured-video-frame is-portrait" style="--vid-w: {{ config('video.editorial.arsik-ikan-mas.lebar') }}; --vid-h: {{ config('video.editorial.arsik-ikan-mas.tinggi') }};">

                            <span class="badge-featured-category">Kuliner khas Batak</span>

                            <div class="scene-ph" data-scene="hill" data-seed="5" aria-hidden="true"></div>

                            <x-video-lazy :item="config('video.editorial.arsik-ikan-mas')" label="Arsik ikan mas, hidangan khas pesta adat Batak" />

                        </div>

                    </div>

                    <div class="col-lg-6 pe-lg-5">

                        <div class="featured-meta">

                            <span><i class="fa-solid fa-map-pin text-teal"></i> Toba</span>

                            <span><i class="fa-solid fa-fish"></i> Hidangan adat</span>

                        </div>

                        <h3 class="featured-title">Arsik ikan mas, hidangan pesta adat Batak</h3>

                        <p class="featured-excerpt">

                            Arsik dibuat dari ikan mas yang dimasak pelan dengan andaliman, kunyit, dan asam. Hidangan ini sering hadir di pesta adat Batak dan jadi salah satu masakan yang paling dikenal dari tanah Toba.

                        </p>

                        <a href="{{ route('kuliner.show', 'arsik-ikan-mas') }}" class="btn btn-aceh-glass">

                            Kenali arsik <i class="fa-solid fa-arrow-right ms-2"></i>

                        </a>

                    </div>

                </div>

                </div>

            </div>



            {{-- 3. DANAU TOBA — portrait 9:16 (kiri) --}}

            <div class="featured-block tema-{{ config('video.editorial.danau-toba.latar.tema') }}" data-sisi-teks="kanan">

                <x-latar-editorial :item="config('video.editorial.danau-toba.latar')" />

                <div class="container">

                <div class="row align-items-center g-4">

                    <div class="col-lg-6">

                        <div class="featured-video-frame is-portrait" style="--vid-w: {{ config('video.editorial.danau-toba.lebar') }}; --vid-h: {{ config('video.editorial.danau-toba.tinggi') }};">

                            <span class="badge-featured-category accent-sky">Wisata alam</span> <!-- GANTI DI SINI: label badge -->

                            <div class="scene-ph" data-scene="lake" data-seed="9" aria-hidden="true"></div>

                            <x-video-lazy :item="config('video.editorial.danau-toba')" label="Danau Toba dan Pulau Samosir" />

                        </div>

                    </div>

                    <div class="col-lg-6 ps-lg-5">

                        <div class="featured-meta">

                            <span><i class="fa-solid fa-map-pin text-teal"></i> Toba dan Samosir</span> <!-- GANTI DI SINI: lokasi -->

                            <span><i class="fa-solid fa-mountain-sun text-teal"></i> Danau vulkanik</span> <!-- GANTI DI SINI: meta kedua -->

                        </div>

                        <!-- GANTI DI SINI: judul, deskripsi, dan tujuan CTA Rumah Bolon -->

                        <h3 class="featured-title">Danau Toba, danau vulkanik terbesar di dunia</h3>

                        <p class="featured-excerpt">

                            Danau Toba dikelilingi perbukitan, dengan Pulau Samosir di tengahnya. Di sekitarnya ada desa-desa adat Batak, tempat kamu bisa melihat rumah adat, tor-tor, dan kehidupan warga setempat.

                        </p>

                        <a href="{{ route('destinasi.index') }}" class="btn btn-aceh-glass">

                            Lihat Danau Toba <i class="fa-solid fa-arrow-right ms-2"></i>

                        </a>

                    </div>

                </div>

                </div>

            </div>



            {{-- 4. BIKA AMBON — portrait 9:16 (kanan) --}}

            <div class="featured-block mb-0 tema-{{ config('video.editorial.bika-ambon.latar.tema') }}" data-sisi-teks="kiri">

                <x-latar-editorial :item="config('video.editorial.bika-ambon.latar')" />

                <div class="container">

                <div class="row align-items-center g-4 flex-lg-row-reverse">

                    <div class="col-lg-6">

                        <div class="featured-video-frame is-portrait" style="--vid-w: {{ config('video.editorial.bika-ambon.lebar') }}; --vid-h: {{ config('video.editorial.bika-ambon.tinggi') }};">

                            <span class="badge-featured-category">Kuliner khas Medan</span> <!-- GANTI DI SINI: label badge -->

                            <div class="scene-ph" data-scene="city" data-seed="11" aria-hidden="true"></div>

                            <x-video-lazy :item="config('video.editorial.bika-ambon')" label="Bika Ambon yang baru diangkat dari panggangan" />

                        </div>

                    </div>

                    <div class="col-lg-6 pe-lg-5">

                        <div class="featured-meta">

                            <span><i class="fa-solid fa-map-pin text-teal"></i> Kota Medan</span> <!-- GANTI DI SINI: lokasi -->

                            <span><i class="fa-solid fa-cookie-bite text-teal"></i> Oleh-oleh</span> <!-- GANTI DI SINI: meta kedua -->

                        </div>

                        <!-- GANTI DI SINI: judul, deskripsi, dan tujuan CTA Sabang -->

                        <h3 class="featured-title">Bika Ambon, salah satu oleh-oleh populer dari Medan</h3>

                        <p class="featured-excerpt">

                            Bika Ambon punya tekstur berserat dan berongga dengan aroma pandan serta santan. Kue ini telah lama dikenal sebagai salah satu pilihan oleh-oleh dari Kota Medan.

                        </p>

                        <a href="{{ route('kuliner.show', 'bika-ambon') }}" class="btn btn-aceh-glass">

                            Kenali Bika Ambon <i class="fa-solid fa-arrow-right ms-2"></i>

                        </a>

                    </div>

                </div>

                </div>

            </div>

    </section>





    <!-- DISCOVER: KULINER POPULER (4 kuliner terbaru dari database) -->

    <section class="section-discover">

        <div class="container">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 gap-3">

                <div>

                    <span class="text-uppercase tracking-wider text-teal fw-bold small">Lihat kuliner</span>

                    <h2 class="font-editorial display-6 fw-bold m-0">Mau coba kuliner khas Sumatera Utara?</h2>

                </div>

                <a href="{{ route('kuliner.index') }}" class="btn btn-aceh-outline">Lihat semua kuliner</a>

            </div>



            {{-- Chip disaring di klien: chip-filter.js mengirim event ke marquee.js yang membangun ulang strip.

                 Baris berdaerah "semua" selalu tampil (F03) --}}

            <div class="d-flex flex-wrap gap-2 mb-4" id="filterDaerahChips" data-chip-filter data-chip-marquee="#marqueeKuliner">

                <x-chip-daerah slug="semua" teks="Semua daerah" :aktif="true" />

                @foreach ($daerahChip as $d)

                    <x-chip-daerah :slug="$d->slug" :teks="$d->nama_daerah" />

                @endforeach

            </div>

        </div>



        {{-- Strip penuh selebar layar: kartu bergeser otomatis ke kiri, looping --}}

        <div class="mq" id="marqueeKuliner" data-marquee role="region" aria-label="Kuliner pilihan">

            <div class="mq-track" id="kulinerPopulerContainer">

                <div class="mq-group">

                    @forelse ($kulinerPopuler as $k)

                        <div class="mq-item" data-daerah="{{ $k->daerah?->slug ?? 'semua' }}">

                            <x-card-mini

                                :href="route('kuliner.show', $k)"

                                badge="Kuliner" tipe="kuliner"

                                :lokasi="$k->daerah?->nama_daerah ?? 'Sumatera Utara'"

                                :judul="$k->nama" :deskripsi="$k->deskripsi" :gambar="$k->gambar_url" :peringkat="$k->peringkat" />

                        </div>

                    @empty

                        <div class="container"><x-pesan-kosong class="py-4 w-100">Belum ada kuliner yang ditampilkan saat ini.</x-pesan-kosong></div>

                    @endforelse

                </div>

            </div>

        </div>

    </section>



    <!-- DISCOVER: BUDAYA & TRADISI PILIHAN (3 budaya terbaru dari database) -->

    <section class="section-discover-budaya">

        <div class="container">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 gap-3">

                <div>

                    <span class="text-uppercase tracking-wider text-teal fw-bold small">Tradisi, seni, dan budaya</span>

                    <h2 class="font-editorial display-6 fw-bold m-0">Mengenal budaya Sumatera Utara</h2>

                </div>

                <a href="{{ route('budaya.index') }}" class="btn btn-aceh-outline">Lihat semua budaya</a>

            </div>



        </div>



        {{-- Arah kebalikan dari strip kuliner (geser ke kanan) supaya dua strip tidak terasa kembar --}}

        <div class="mq mq-kanan" id="marqueeBudaya" data-marquee role="region" aria-label="Budaya dan tradisi pilihan">

            <div class="mq-track" id="budayaPilihanContainer">

                <div class="mq-group">

                    @forelse ($budayaPilihan as $b)

                        <div class="mq-item">

                            <x-card-mini

                                :href="route('budaya.index', ['q' => $b->nama])"

                                :badge="$b->kategori?->nama_kategori ?: 'Budaya'" tipe="budaya"

                                :lokasi="$b->daerah?->nama_daerah ?? 'Sumatera Utara'"

                                :judul="$b->nama" :deskripsi="$b->deskripsi" :gambar="$b->gambar_url"

                                tombolTeks="Kenali lebih jauh" />

                        </div>

                    @empty

                        <div class="container"><x-pesan-kosong class="py-4 w-100">Belum ada informasi budaya yang ditampilkan saat ini.</x-pesan-kosong></div>

                    @endforelse

                </div>

            </div>

        </div>

    </section>



    <!-- IDENTITAS SUMATERA UTARA: Rumah Bolon di tepi Toba + tiga salam (Batak, Karo, Nias) -->
    <div class="container">
        <section class="section-editorial-identity identitas-sumut">
            <div class="row align-items-center g-5 px-4 px-md-5">
                <div class="col-lg-5">
                    <figure class="rb-figure">
                        <span class="rb-slab" aria-hidden="true"></span>
                        <div class="rb-frame">
                            @php $fotoIdentitas = \App\Support\Media::url(config('video.identitas.foto')); @endphp
                            @if ($fotoIdentitas)
                                <img class="rb-foto" src="{{ $fotoIdentitas }}" alt="Masyarakat Batak di Sumatera Utara" width="800" height="1000" loading="lazy" decoding="async" referrerpolicy="no-referrer"
                                     onerror="var f=this.nextElementSibling; if(f){f.hidden=false;} this.remove();">
                            @endif
                            <div @if ($fotoIdentitas) hidden @endif>
                            <svg viewBox="0 0 480 560" role="img" aria-label="Ilustrasi: Rumah Bolon di tepi Danau Toba" focusable="false">
                                <defs><linearGradient id="rbLangit" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#FFF9F1"/><stop offset="1" stop-color="#F4C5D5"/></linearGradient></defs>
                                <rect width="480" height="560" fill="url(#rbLangit)"/>
                                <circle cx="362" cy="118" r="36" fill="#FFB52E"/>
                                <path d="M0 340L92 226l82 70 98-108 104 100 104-62v234H0z" fill="#71317D"/>
                                <path d="M0 372L120 286l92 62 110-84 158 96v212H0z" fill="#54205F" opacity=".9"/>
                                <rect y="372" width="480" height="188" fill="#F4C5D5"/>
                                <g stroke="#FFF9F1" stroke-width="3" stroke-linecap="round" fill="none" opacity=".7"><path d="M40 410q20-8 40 0t40 0"/><path d="M330 396q20-8 40 0t40 0"/><path d="M90 520q24-8 48 0t48 0"/><path d="M300 500q24-8 48 0t48 0"/></g>
                                <path d="M46 468Q240 392 434 468V560H46z" fill="#3F1748"/>
                                <g fill="#29212B">
                                    <path transform="translate(112 232) scale(4.3)" d="M8 38C16 34 17 24 20 13c5 8 8 11 12 11s7-3 12-11c3 11 4 21 12 25z"/>
                                    <path d="M150 396l10-52h160l10 52z"/>
                                    <rect x="146" y="396" width="188" height="9"/>
                                    <g><rect x="160" y="405" width="9" height="46"/><rect x="198" y="405" width="9" height="46"/><rect x="236" y="405" width="9" height="46"/><rect x="274" y="405" width="9" height="46"/><rect x="311" y="405" width="9" height="46"/></g>
                                </g>
                                <rect x="165" y="354" width="150" height="6" fill="#B72D63"/><rect x="165" y="360" width="150" height="6" fill="#F8E8D0"/>
                                <rect x="222" y="372" width="36" height="24" fill="#B72D63"/><path d="M222 372h36l-18 12z" fill="#F8E8D0"/>
                                <g fill="#29212B" opacity=".92"><path transform="translate(388 392) scale(1.9)" d="M8 38C16 34 17 24 20 13c5 8 8 11 12 11s7-3 12-11c3 11 4 21 12 25z"/><rect x="396" y="430" width="52" height="5"/><rect x="402" y="435" width="5" height="22"/><rect x="438" y="435" width="5" height="22"/></g>
                            </svg>
                            </div>
                        </div>
                        <figcaption class="rb-caption"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> {{ $fotoIdentitas ? 'Masyarakat Batak, Sumatera Utara' : 'Rumah Bolon, tepi Danau Toba' }}</figcaption>
                    </figure>
                </div>
                <div class="col-lg-7">
                    <span class="text-uppercase tracking-wider text-meadow fw-bold small">Identitas Sumatera Utara</span>
                    <h2 class="font-editorial display-6 fw-bold text-white mt-2 mb-3">Satu provinsi, banyak salam</h2>
                    <p class="text-light mb-4">
                        Sumatera Utara dihuni banyak suku, dan tiap suku punya cara menyapa sendiri. Dari Medan yang sibuk sampai kampung di tepi Danau Toba, salam, kain ulos, dan tor-tor masih jadi bagian hidup sehari-hari.
                    </p>
                    <div class="salam-grid">
                        <div class="salam-card salam-batak">
                            <span class="salam-kata">Horas</span>
                            <span class="salam-suku">Batak</span>
                            <p>Toba, Samosir, dan Tapanuli. Salam untuk kamu yang datang.</p>
                        </div>
                        <div class="salam-card salam-karo">
                            <span class="salam-kata">Mejuah-juah</span>
                            <span class="salam-suku">Karo</span>
                            <p>Tanah Karo dan Berastagi. Salam agar kamu sehat dan sejahtera.</p>
                        </div>
                        <div class="salam-card salam-nias">
                            <span class="salam-kata">Ya'ahowu</span>
                            <span class="salam-suku">Nias</span>
                            <p>Nias dan Nias Selatan. Salam dengan doa agar kamu diberkati.</p>
                        </div>
                    </div>
                    <div class="ulos-strip" aria-hidden="true"><i></i><i></i><i></i></div>
                    <div class="d-flex flex-wrap gap-2 pt-1">
                        <a href="{{ route('budaya.index') }}" class="btn btn-aceh-accent">
                            Kenali budaya lokal <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                        <a href="{{ route('kuliner.index') }}" class="btn btn-aceh-glass">
                            Cicipi kulinernya
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- PENGALAMAN PENGUNJUNG (12 terbaru; yang terbaru aktif) -->

    <section class="section-community">

        <div class="container">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">

                <div>

                    <span class="text-uppercase tracking-wider text-teal fw-bold small">Cerita pengunjung</span>

                    <h2 class="font-editorial fw-bold m-0 mt-1">Cerita perjalanan dari para pengunjung</h2>

                </div>

                <button type="button" class="btn btn-aceh-primary" data-bs-toggle="modal" data-bs-target="#modalUnggahPengalaman">

                    <i class="fa-solid fa-camera me-2"></i>Bagikan ceritamu

                </button>

            </div>



            <div id="carouselPengalaman" class="carousel slide" data-bs-ride="carousel">

                <div class="carousel-inner" id="carouselPengalamanInner">

                    @if ($pengalaman->isEmpty())

                        <div class="carousel-item active" data-kosong>

                            <x-pesan-kosong class="mb-0 w-100">Belum ada cerita pengunjung. Bagikan ceritamu dan jadilah yang pertama.</x-pesan-kosong>

                        </div>

                    @else

                        {{-- urut lama -> baru, slide terakhir (terbaru) yang aktif, seperti versi lama --}}

                        @foreach ($pengalaman->reverse()->values() as $p)

                            <x-slide-pengalaman :p="$p" :aktif="$loop->last" />

                        @endforeach

                    @endif

                </div>



                <div class="d-flex justify-content-end gap-2 mt-4">

                    <button class="carousel-nav-btn" type="button" data-bs-target="#carouselPengalaman" data-bs-slide="prev" aria-label="Sebelumnya">

                        <i class="fa-solid fa-chevron-left"></i>

                    </button>

                    <button class="carousel-nav-btn" type="button" data-bs-target="#carouselPengalaman" data-bs-slide="next" aria-label="Berikutnya">

                        <i class="fa-solid fa-chevron-right"></i>

                    </button>

                </div>

            </div>

        </div>

    </section>



    <!-- CTA -->

    <section class="section-closing-cta">

        <div class="container">

            <div class="row justify-content-center">

                <div class="col-lg-8 text-center">

                    <span class="text-uppercase tracking-wider text-meadow fw-bold small">Sedang merencanakan perjalanan?</span>

                    <h2 class="cta-heading mt-2">Mulai jelajahi Sumatera Utara</h2>

                    <p class="lead opacity-90 mb-5 text-light">

                        Cari kuliner yang ingin dicoba, kenali budaya setempat, dan temukan destinasi untuk dikunjungi. Mulai susun perjalananmu dan lihat apa saja yang ditawarkan Sumatera Utara.

                    </p>

                    <div class="d-flex flex-wrap justify-content-center gap-3">

                        <a href="{{ route('kuliner.index') }}" class="btn btn-lg btn-aceh-accent px-5 py-3 fs-6">

                            Lihat kuliner

                        </a>

                        <a href="{{ route('budaya.index') }}" class="text-light btn btn-lg btn-aceh-glass px-5 py-3 fs-6">

                            Lihat budaya

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>





    <x-modal-pengalaman :daftar-daerah="$daftarDaerah" />

@endsection



@push('js')

    <script src="{{ asset('assets/js/chip-filter.js') }}" defer></script>

    <script src="{{ asset('assets/js/marquee.js') }}" defer></script>

    <script src="{{ asset('assets/js/luminance.js') }}" defer></script>

    <script src="{{ asset('assets/js/pengalaman.js') }}" defer></script>

    <script src="{{ asset('assets/js/suka-pengalaman.js') }}" defer></script>

@endpush
