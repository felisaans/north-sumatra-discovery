/*
 * tiktok-bg.js — background video TikTok untuk header halaman Kuliner.
 * TikTok background video for the Kuliner page header: muted, no controls, loops a time segment.
 *
 * Markup: <x-video-youtube> (kunci 'tiktok') -> <div data-tt-bg data-tt-id data-tt-start data-tt-end>
 *
 * Memakai player resmi TikTok (https://www.tiktok.com/player/v1/<id>) yang dikontrol lewat postMessage:
 *   kirim  : {type:'play'|'pause'|'mute'|'seekTo', value, 'x-tiktok-player': true}
 *   terima : {type:'onPlayerReady'|'onStateChange'|'onCurrentTime'|'onMute'|'onError', value, 'x-tiktok-player': true}
 *
 * Perilaku (sama seperti youtube-bg.js):
 *   - Dimuat SETELAH halaman selesai + idle, supaya render awal tidak melambat.
 *   - Potongan start..end diputar berulang (seekTo(start) saat mendekati batas akhir).
 *   - Pause saat header keluar layar / tab disembunyikan.
 *   - reduced-motion / hemat data -> tidak memuat apa pun (desain lama tetap tampil).
 *   - Player tidak siap dalam 10 detik / error / video tidak boleh di-embed -> elemen dibuang, header kembali ke desain lama.
 *   - Class "video-aktif" dipasang di header HANYA setelah video benar-benar PLAYING.
 */
(() => {
    'use strict';

    const wadah = Array.from(document.querySelectorAll('[data-tt-bg]'));
    if (!wadah.length) return;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    if (reduceMotion.matches || (conn && conn.saveData === true)) {
        wadah.forEach((el) => el.remove());
        return;
    }

    const BATAS_SIAP = 10000;   // ms — lewat ini tanpa onPlayerReady = dianggap gagal
    const MARGIN_AKHIR = 0.4;   // detik — lompat ke awal sedikit sebelum batas akhir
    const ASAL = /^https:\/\/([a-z0-9-]+\.)?tiktok\.com$/i;

    function pasang(el) {
        const header = el.parentElement;
        const id = el.dataset.ttId;
        const mulai = Number(el.dataset.ttStart) || 0;
        const akhir = Number(el.dataset.ttEnd) || 0;
        const frame = el.querySelector('.yt-bg__frame');
        if (!id || !frame) return el.remove();

        const params = new URLSearchParams({
            controls: 0, progress_bar: 0, play_button: 0, volume_control: 0, fullscreen_button: 0,
            timestamp: 0, music_info: 0, description: 0, rel: 0, native_context_menu: 0, closed_caption: 0,
            autoplay: 1, muted: 1, loop: 1,
        });
        const iframe = document.createElement('iframe');
        iframe.src = `https://www.tiktok.com/player/v1/${encodeURIComponent(id)}?${params}`;
        iframe.title = '';
        iframe.allow = 'autoplay; encrypted-media';
        iframe.setAttribute('referrerpolicy', 'strict-origin-when-cross-origin');
        iframe.setAttribute('tabindex', '-1');
        iframe.setAttribute('aria-hidden', 'true');

        let siap = false;
        let gagal = false;
        let terlihat = true;
        let sudahLompatAwal = false;
        let waktuLompat = 0;

        const kirim = (type, value) => {
            try {
                iframe.contentWindow.postMessage({ type, value, 'x-tiktok-player': true }, '*');
            } catch (e) { /* abaikan */ }
        };
        const lompatKeAwal = () => {
            const sekarang = Date.now();
            if (sekarang - waktuLompat < 800) return;   // cegah seek beruntun saat event waktu datang rapat
            waktuLompat = sekarang;
            kirim('seekTo', mulai);
            kirim('play');
        };

        const timer = setTimeout(() => { if (!siap) gagalMuat(); }, BATAS_SIAP);

        function gagalMuat() {
            if (gagal) return;
            gagal = true;
            clearTimeout(timer);
            window.removeEventListener('message', terima);
            header.classList.remove('video-aktif');
            el.remove();   // header kembali ke desain lama
        }

        function terima(e) {
            if (e.source !== iframe.contentWindow || !ASAL.test(e.origin)) return;
            const d = e.data;
            if (!d || d['x-tiktok-player'] !== true) return;

            switch (d.type) {
                case 'onPlayerReady':
                    siap = true;
                    clearTimeout(timer);
                    kirim('mute');
                    if (mulai > 0) kirim('seekTo', mulai);
                    if (terlihat && !document.hidden) kirim('play');
                    break;
                case 'onStateChange':
                    // 1 = playing
                    if (d.value === 1) {
                        if (!sudahLompatAwal && mulai > 0) { sudahLompatAwal = true; kirim('seekTo', mulai); }
                        el.classList.add('is-playing');
                        header.classList.add('video-aktif');
                    }
                    break;
                case 'onCurrentTime': {
                    const t = d.value && typeof d.value.currentTime === 'number' ? d.value.currentTime : null;
                    if (t === null) break;
                    // di luar potongan (mis. video mulai dari 0:00 sebelum seek) atau melewati batas akhir -> ke awal
                    if ((akhir && t >= akhir - MARGIN_AKHIR) || t < mulai - 1) lompatKeAwal();
                    break;
                }
                case 'onError':
                    gagalMuat();   // video dihapus / tidak boleh di-embed
                    break;
            }
        }
        window.addEventListener('message', terima);
        frame.appendChild(iframe);

        // Pause saat header keluar layar / Pause when the header leaves the viewport
        if ('IntersectionObserver' in window) {
            new IntersectionObserver((entries) => {
                terlihat = entries[0].isIntersecting;
                if (!siap || gagal) return;
                kirim(terlihat && !document.hidden ? 'play' : 'pause');
            }, { threshold: 0.05 }).observe(header);
        }

        // Pause saat tab disembunyikan / Pause when the tab is hidden
        document.addEventListener('visibilitychange', () => {
            if (!siap || gagal) return;
            kirim(document.hidden ? 'pause' : (terlihat ? 'play' : 'pause'));
        });
    }

    function mulaiSemua() { wadah.forEach(pasang); }
    const tunda = () => ('requestIdleCallback' in window)
        ? window.requestIdleCallback(mulaiSemua, { timeout: 2000 })
        : setTimeout(mulaiSemua, 800);

    if (document.readyState === 'complete') tunda();
    else window.addEventListener('load', tunda, { once: true });
})();
