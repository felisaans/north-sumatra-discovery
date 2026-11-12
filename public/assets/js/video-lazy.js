/*
 * video-lazy.js — satu loader reusable untuk SEMUA video beranda (hero + Sorotan Editorial).
 * One reusable loader for ALL homepage videos (hero + editorial).
 *
 * Markup (lihat resources/views/components/video-lazy.blade.php):
 *   <video data-video data-video-mode="eager|lazy" data-src="/storage/..mp4" poster="..."></video>
 *
 *   mode "eager" (hero)      -> src langsung diisi saat script jalan (sudah di atas fold).
 *   mode "lazy"  (editorial) -> src baru diisi saat video mendekati viewport (rootMargin).
 *
 * Aturan pemutaran (semua mode) / Playback rules (all modes):
 *   - play  : saat >= 25% video terlihat
 *   - pause : saat keluar viewport / tab disembunyikan
 *   - src TIDAK dihapus saat keluar viewport -> scroll naik-turun tidak download ulang
 *
 * Status bisa dilihat di atribut data-video-state:
 *   idle | loading | playing | paused | blocked | failed | skipped
 */
(() => {
    'use strict';

    const videos = Array.from(document.querySelectorAll('video[data-video]'));
    if (!videos.length) return;

    // ---- Config default; bisa di-override per elemen lewat data-video-margin ----
    const DEFAULT_LOAD_MARGIN = '300px 0px'; // mulai load ±300px SEBELUM terlihat (vertikal saja)
    const PLAY_THRESHOLD = 0.25;             // minimal 25% terlihat baru play

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    const hasIO = 'IntersectionObserver' in window;

    const visible = new Set();     // video yang sedang di viewport
    const needGesture = new Set(); // video yang autoplay-nya ditolak browser
    const loadObservers = new Map();

    // Pengguna minta gerakan dikurangi / hemat data -> poster saja, tidak ada request video.
    const mustSkip = () => reduceMotion.matches || (conn && conn.saveData === true);

    const setState = (v, s) => { v.dataset.videoState = s; };

    // Fraksi video yang terlihat, dihitung dari posisi geometris asli (boundingClientRect).
    // Sengaja TIDAK memakai isIntersecting/intersectionRatio: animasi tirai (reveal.js) memotong frame video dengan
    // clip-path, dan IntersectionObserver menganggap bagian terpotong itu "tidak terlihat" -> video di-pause / tidak
    // di-load selama animasi, dan di layar sempit bisa macet. Geometri tidak terpengaruh clip-path.
    function fraksiTerlihat(e) {
        const r = e.boundingClientRect;
        const b = e.rootBounds || { top: 0, bottom: window.innerHeight, left: 0, right: window.innerWidth };
        const h = Math.min(r.bottom, b.bottom) - Math.max(r.top, b.top);
        const w = Math.min(r.right, b.right) - Math.max(r.left, b.left);
        if (h <= 0 || w <= 0 || r.width <= 0 || r.height <= 0) return 0;
        return (h * w) / (r.width * r.height);
    }

    // ---- Load: isi src dari data-src (idempotent — aman dipanggil berkali-kali) ----
    function load(v) {
        if (v.dataset.videoLoaded || mustSkip()) return;
        const src = v.dataset.src;
        if (!src) return;

        v.dataset.videoLoaded = '1';
        v.muted = true;
        v.defaultMuted = true;
        v.preload = 'auto';
        setState(v, 'loading');

        v.addEventListener('error', () => setState(v, 'failed'), { once: true }); // poster tetap tampil
        v.addEventListener('playing', () => setState(v, 'playing'));
        v.addEventListener('pause', () => { if (!v.ended && v.dataset.videoState === 'playing') setState(v, 'paused'); });

        v.src = src;
        v.load();
        if (visible.has(v)) play(v);
    }

    // ---- Play / pause ----
    function play(v) {
        if (mustSkip() || v.dataset.videoState === 'failed' || !v.dataset.videoLoaded) return;
        if (document.hidden) return;
        const p = v.play();
        if (p && typeof p.catch === 'function') {
            p.catch((err) => {
                if (err && err.name === 'NotAllowedError') {   // autoplay diblokir (mis. mode hemat daya iOS)
                    needGesture.add(v);
                    setState(v, 'blocked');                    // poster tetap tampil
                }
                // AbortError (play() terpotong pause()) sengaja diabaikan
            });
        }
    }

    function pause(v) {
        if (!v.paused) v.pause();
    }

    // ---- Observer pemutaran: berlaku untuk hero & editorial ----
    const playObserver = hasIO ? new IntersectionObserver((entries) => {
        entries.forEach((e) => {
            const v = e.target;
            if (fraksiTerlihat(e) >= PLAY_THRESHOLD) {
                visible.add(v);
                load(v);   // jaring pengaman jika observer load belum sempat memicu
                play(v);
            } else {
                visible.delete(v);
                pause(v);
            }
        });
    }, { threshold: [0, PLAY_THRESHOLD, 0.5, 1] }) : null;   // beberapa ambang: callback ikut terpanggil saat clip-path tirai selesai

    // ---- Observer load (khusus lazy): satu observer per nilai rootMargin ----
    function loadObserverFor(margin) {
        if (!loadObservers.has(margin)) {
            const io = new IntersectionObserver((entries) => {
                entries.forEach((e) => {
                    if (e.isIntersecting || fraksiTerlihat(e) > 0) {
                        load(e.target);
                        io.unobserve(e.target);  // cukup sekali
                    }
                });
            }, { rootMargin: margin });
            loadObservers.set(margin, io);
        }
        return loadObservers.get(margin);
    }

    // ---- Inisialisasi ----
    function init() {
        videos.forEach((v) => {
            v.muted = true;
            if (mustSkip()) { setState(v, 'skipped'); return; }
            if (v.dataset.videoInit) return;
            v.dataset.videoInit = '1';
            setState(v, 'idle');

            const eager = v.dataset.videoMode === 'eager';
            if (eager) load(v);   // hero: tanpa menunggu observer apa pun

            if (hasIO) {
                playObserver.observe(v);
                if (!eager) loadObserverFor(v.dataset.videoMargin || DEFAULT_LOAD_MARGIN).observe(v);
            }
            // Tanpa IntersectionObserver: hanya hero yang jalan; editorial tetap poster (aman & hemat).
        });
    }

    // ---- Tab disembunyikan -> pause; kembali -> lanjutkan yang terlihat ----
    document.addEventListener('visibilitychange', () => {
        videos.forEach((v) => {
            if (document.hidden) pause(v);
            else if (visible.has(v)) play(v);
        });
    });

    // ---- Autoplay ditolak -> coba lagi sekali setelah interaksi pertama user ----
    const retry = () => {
        needGesture.forEach((v) => { if (visible.has(v)) play(v); });
        needGesture.clear();
    };
    ['pointerdown', 'touchstart', 'keydown'].forEach((evt) =>
        window.addEventListener(evt, retry, { once: true, passive: true }));

    // ---- Preferensi reduced-motion diubah saat halaman terbuka ----
    reduceMotion.addEventListener('change', () => {
        if (reduceMotion.matches) videos.forEach((v) => { pause(v); setState(v, 'skipped'); });
        else init();
    });

    init();
})();
