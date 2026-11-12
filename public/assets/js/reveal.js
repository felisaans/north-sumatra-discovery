/*
 * reveal.js — animasi "muncul" untuk teks & media saat di-scroll ke layar; MAIN ULANG setiap kali
 * elemen masuk layar lagi (scroll turun maupun naik).
 *
 * Cara kerja:
 *   1. Elemen di dalam #konten ditandai otomatis lewat daftar selector di bawah (tidak perlu mengubah Blade).
 *      Cara menambah: masukkan selector ke daftar yang sesuai. Cara mengecualikan: atribut data-no-reveal.
 *   2. Jeda (stagger): elemen dalam satu kolom/grup mendapat jeda 0, 90, 180ms ... (maks 8 langkah).
 *   3. Dua IntersectionObserver supaya tidak berkedip di batas layar (keputusan memakai posisi geometris, bukan
 *      isIntersecting, agar tidak terpengaruh clip-path animasi tirai):
 *        masuk  -> tambah .is-in saat 8% area bawah layar terlewati
 *        keluar -> lepas .is-in hanya setelah elemen benar-benar di luar layar (animasi siap main ulang)
 *   4. Reduced-motion / tanpa IntersectionObserver -> tidak melakukan apa pun; semua tampil biasa.
 * Gaya & keyframe: assets/css/reveal.css
 */
(() => {
    'use strict';
    const html = document.documentElement;
    window.__rv = true;   // tanda untuk fail-safe di <head>
    if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        html.classList.remove('js-reveal');
        return;
    }
    const scope = document.getElementById('konten') || document.body;

    // ---- DAFTAR SELECTOR (ubah di sini) ----
    const SKIP   = '.aceh-navbar, .aceh-footer, .mq, .modal, .carousel, .experience-card, .visually-hidden, .skip-link, [data-no-reveal]';
    const PANEL  = '.custom-card-bg, .filter-section, .hero-floating-card, .hero-badge-top, .dest-content-box, .not-logged-banner';  // satu panel utuh, isi tidak dianimasi terpisah
    const MEDIA  = '.hero-kubah-bayang, .hero-slab, .featured-video-frame, .collage-main, .collage-sub, .collage-slab, .dest-image-wrapper, .editorial-img, .ornate-frame-banner, .ornate-frame-circle';
    const HEAD   = 'h1, h2, h3, h4, .hero-heading, .featured-title, .cta-heading';
    const TEXT   = 'p, blockquote, .hero-tag-pill, .featured-meta, .identity-bullet, .tracking-wider, .lead';
    const CTRL   = '.btn, .search-box-pill, [data-chip-filter]';
    const WRAP   = '.mq, .carousel';   // pembungkus besar: cukup fade-naik sekali

    const tagged = new Set();
    const items = [];
    const inSkip = (el) => !!el.closest(SKIP);
    const inPanel = (el) => !!(el.parentElement && el.parentElement.closest(PANEL));
    function tag(el, jenis) {
        if (tagged.has(el)) return;
        tagged.add(el);
        el.classList.add(jenis === 'words' ? 'rv-words' : 'rv', ...(jenis === 'words' ? [] : ['rv-' + jenis]));
        items.push({ el, jenis });
    }

    function pecahKata(el) {
        const teks = el.textContent.trim().replace(/\s+/g, ' ');
        const frag = document.createDocumentFragment();
        const kata = teks.split(' ');
        kata.forEach((k, i) => {
            const luar = document.createElement('span'); luar.className = 'rv-w';
            const dalam = document.createElement('span'); dalam.className = 'rv-wi';
            dalam.style.setProperty('--wi', Math.min(i, 14));
            dalam.textContent = k;
            luar.appendChild(dalam); frag.appendChild(luar);
            if (i < kata.length - 1) frag.appendChild(document.createTextNode(' '));
        });
        el.setAttribute('aria-label', teks);
        el.textContent = '';
        el.appendChild(frag);
    }

    // urutan penandaan = prioritas: panel -> media -> judul -> teks -> kontrol -> pembungkus
    scope.querySelectorAll(PANEL).forEach((el) => { if (!inSkip(el) && !inPanel(el)) tag(el, 'pop'); });
    scope.querySelectorAll(MEDIA).forEach((el) => { if (!inSkip(el) && !inPanel(el)) tag(el, 'curtain'); });
    scope.querySelectorAll(HEAD).forEach((el) => {
        if (inSkip(el) || inPanel(el) || el.closest('.rv-words')) return;
        if (el.children.length === 0 && el.textContent.trim().length > 0 && !el.classList.contains('hero-heading')) {
            pecahKata(el); tag(el, 'words');
        } else {
            tag(el, 'up');
        }
    });
    scope.querySelectorAll(TEXT).forEach((el) => { if (!inSkip(el) && !inPanel(el) && !el.closest('.rv-words') && !el.closest(MEDIA)) tag(el, 'up'); });
    scope.querySelectorAll(CTRL).forEach((el) => { if (!inSkip(el) && !inPanel(el)) tag(el, 'pop'); });
    scope.querySelectorAll(WRAP).forEach((el) => { if (!(el.parentElement && el.parentElement.closest(SKIP))) tag(el, 'fade'); });

    // ---- jeda berurutan per kolom/grup, mengikuti urutan dokumen ----
    items.sort((a, b) => (a.el.compareDocumentPosition(b.el) & Node.DOCUMENT_POSITION_FOLLOWING ? -1 : 1));
    const hitung = new Map();
    items.forEach(({ el, jenis }) => {
        const grup = el.closest('[class*="col-"], .hero-visual-container, .collage-wrapper, .container') || scope;
        const n = hitung.get(grup) || 0;
        hitung.set(grup, n + 1);
        el.style.setProperty('--rv-d', Math.min(n, 8) * 90 + 'ms');
        if (jenis === 'curtain') {                       // tirai terbuka dari sisi luar elemen
            const r = el.getBoundingClientRect();
            const lebar = r.width > window.innerWidth * 0.8;
            const kiri = r.left + r.width / 2 < window.innerWidth / 2;
            el.style.setProperty('--rv-from', lebar ? 'inset(0 0 100% 0)' : kiri ? 'inset(0 100% 0 0)' : 'inset(0 0 0 100%)');
        }
    });

    // ---- Observer ----
    // PENTING: keputusan masuk/keluar memakai posisi geometris asli elemen (boundingClientRect), BUKAN e.isIntersecting.
    // Animasi tirai (rv-curtain) memotong elemen dengan clip-path; IntersectionObserver menghitung bagian yang
    // terpotong itu sebagai "tidak terlihat" -> observer "keluar" mencabut .is-in -> animasi batal -> masuk lagi -> berulang
    // (kedip tanpa henti; di layar sempit elemen malah macet di opacity 0). Memakai geometri memutus lingkaran itu.
    const batas = (e) => e.rootBounds || { top: 0, bottom: window.innerHeight };
    const tumpang = (e) => {                      // elemen secara geometris beririsan dengan area pengamatan?
        const r = e.boundingClientRect, b = batas(e);
        return r.bottom > b.top && r.top < b.bottom && r.height >= 0;
    };
    const masuk = new IntersectionObserver((es) => es.forEach((e) => {
        if (e.isIntersecting || tumpang(e)) e.target.classList.add('is-in');
    }), { rootMargin: '0px 0px -8% 0px', threshold: 0.01 });
    const keluar = new IntersectionObserver((es) => es.forEach((e) => {
        if (!tumpang(e)) e.target.classList.remove('is-in');   // lepas hanya bila benar-benar di luar layar
    }), { threshold: 0 });
    items.forEach(({ el }) => { masuk.observe(el); keluar.observe(el); });
})();
