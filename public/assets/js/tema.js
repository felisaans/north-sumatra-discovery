/*
 * tema.js — toggle dark / light mode.
 * Tema awal sudah dipasang oleh skrip kecil di <head> (default DARK). File ini hanya mengurus:
 *   - klik tombol .js-tema-toggle  -> ganti tema + simpan di localStorage ("tema")
 *   - label/aria tombol, meta theme-color
 *   - sinkron antar tab (event "storage")
 * Initial theme is set by the inline <head> script; this file handles the toggle and persistence.
 */
(() => {
    'use strict';

    const KUNCI = 'tema';
    const root = document.documentElement;
    const WARNA = { dark: '#17131A', light: '#F8E8D0' };   // untuk <meta name="theme-color"> (bar browser mobile)

    const sekarang = () => (root.getAttribute('data-theme') === 'light' ? 'light' : 'dark');

    function perbaruiTampilan(tema) {
        document.querySelectorAll('.js-tema-toggle').forEach((tombol) => {
            const gelap = tema === 'dark';
            tombol.setAttribute('aria-pressed', String(gelap));
            tombol.setAttribute('aria-label', gelap ? 'Ganti ke tema terang' : 'Ganti ke tema gelap');
            tombol.setAttribute('title', gelap ? 'Tema terang' : 'Tema gelap');
        });
        const meta = document.querySelector('meta[name="theme-color"]');
        if (meta) meta.setAttribute('content', WARNA[tema]);
    }

    function terapkan(tema, simpan) {
        root.classList.add('tema-transisi');                    // transisi warna halus, hanya sesaat
        root.setAttribute('data-theme', tema);
        root.setAttribute('data-bs-theme', tema);
        perbaruiTampilan(tema);
        if (simpan) { try { localStorage.setItem(KUNCI, tema); } catch (e) { /* mode privat: abaikan */ } }
        window.setTimeout(() => root.classList.remove('tema-transisi'), 450);
        document.dispatchEvent(new CustomEvent('tema:berubah', { detail: { tema } }));
    }

    document.addEventListener('click', (e) => {
        if (e.target.closest('.js-tema-toggle')) terapkan(sekarang() === 'dark' ? 'light' : 'dark', true);
    });

    // tab lain mengganti tema -> ikut berubah
    window.addEventListener('storage', (e) => {
        if (e.key === KUNCI && (e.newValue === 'light' || e.newValue === 'dark') && e.newValue !== sekarang()) {
            terapkan(e.newValue, false);
        }
    });

    perbaruiTampilan(sekarang());   // sinkronkan label tombol dengan tema awal
})();
