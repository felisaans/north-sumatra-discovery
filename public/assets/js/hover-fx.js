/*
 * hover-fx.js — efek kursor untuk kartu kaca (Beranda): miring 3D ringan + kilau yang mengikuti kursor.
 * Hanya jalan di perangkat dengan kursor (hover: hover) dan bila animasi tidak dimatikan.
 * Mengatur CSS variable --rx, --ry (sudut) dan --mx, --my (posisi kilau) pada kartu; gaya ada di editorial.css.
 * GANTI DI SINI: MAKS_SUDUT = seberapa miring kartu (derajat).
 */
(() => {
    'use strict';
    if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const MAKS_SUDUT = 4;
    const SEL = '.discovery-card-modern, .news-ticker-card';
    let aktif = null, bingkai = 0, e0 = null;

    function terapkan() {
        bingkai = 0;
        if (!aktif || !e0) return;
        const r = aktif.getBoundingClientRect();
        const x = (e0.clientX - r.left) / r.width;     // 0..1
        const y = (e0.clientY - r.top) / r.height;
        aktif.style.setProperty('--ry', ((x - 0.5) * 2 * MAKS_SUDUT).toFixed(2) + 'deg');
        aktif.style.setProperty('--rx', ((0.5 - y) * 2 * MAKS_SUDUT).toFixed(2) + 'deg');
        aktif.style.setProperty('--mx', (x * 100).toFixed(1) + '%');
        aktif.style.setProperty('--my', (y * 100).toFixed(1) + '%');
    }
    function lepas(el) {
        if (!el) return;
        ['--rx', '--ry'].forEach((k) => el.style.removeProperty(k));
    }

    document.addEventListener('pointermove', (e) => {
        if (e.pointerType !== 'mouse') return;
        const kartu = e.target.closest ? e.target.closest(SEL) : null;
        if (kartu !== aktif) { lepas(aktif); aktif = kartu; }
        if (!aktif) return;
        e0 = e;
        if (!bingkai) bingkai = requestAnimationFrame(terapkan);
    }, { passive: true });

    document.addEventListener('pointerleave', () => { lepas(aktif); aktif = null; }, true);
})();
