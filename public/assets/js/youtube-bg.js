/*
 * youtube-bg.js — background video YouTube untuk header halaman (Kuliner, Budaya, Destinasi).
 * YouTube background video for page headers: muted, no controls, loops a time segment.
 *
 * Markup: <x-video-youtube> -> <div data-yt-bg data-yt-id data-yt-start data-yt-end>
 *
 * Perilaku / Behaviour:
 *   - Dimuat SETELAH halaman selesai (event load + idle) agar tidak memperlambat render awal.
 *   - Potongan start..end diputar berulang (dipantau tiap 250 ms, lalu seekTo(start)).
 *   - Pause saat header keluar layar atau tab disembunyikan; lanjut saat terlihat lagi.
 *   - reduced-motion / hemat data (Save-Data) -> tidak memuat apa pun (desain lama tetap tampil).
 *   - Video gagal / embed dilarang -> elemen dibuang, header kembali ke desain lama.
 *   - Class "video-aktif" dipasang di header HANYA setelah video benar-benar PLAYING
 *     (CSS lalu mengganti teks jadi terang di atas overlay gelap).
 */
(() => {
    'use strict';

    const wadah = Array.from(document.querySelectorAll('[data-yt-bg]'));
    if (!wadah.length) return;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    if (reduceMotion.matches || (conn && conn.saveData === true)) {
        wadah.forEach((el) => el.remove());   // hapus lapisan kosong; header = desain lama
        return;
    }

    const INTERVAL_PANTAU = 250;   // ms — seberapa sering cek posisi putar
    const MARGIN_AKHIR = 0.3;      // detik — lompat ke awal sedikit sebelum batas akhir

    // ---- Muat YouTube IFrame API satu kali / load the API once ----
    let janjiApi = null;
    function muatApi() {
        if (janjiApi) return janjiApi;
        janjiApi = new Promise((selesai) => {
            if (window.YT && window.YT.Player) return selesai(true);
            const sebelumnya = window.onYouTubeIframeAPIReady;
            window.onYouTubeIframeAPIReady = () => {
                if (typeof sebelumnya === 'function') sebelumnya();
                selesai(true);
            };
            const s = document.createElement('script');
            s.src = 'https://www.youtube.com/iframe_api';
            s.async = true;
            s.onerror = () => selesai(false);   // diblokir / offline -> desain lama
            document.head.appendChild(s);
        });
        return janjiApi;
    }

    // ---- Satu header = satu player / one player per header ----
    function pasang(el) {
        const header = el.parentElement;
        const id = el.dataset.ytId;
        const mulai = Number(el.dataset.ytStart) || 0;
        const akhir = Number(el.dataset.ytEnd) || 0;
        const tempat = el.querySelector('.yt-bg__player');
        if (!id || !tempat) return el.remove();

        let player = null;
        let siap = false;
        let gagal = false;
        let terlihat = true;
        let pemantau = null;

        const hentikanPantau = () => { if (pemantau) { clearInterval(pemantau); pemantau = null; } };
        const lompatKeAwal = () => { player.seekTo(mulai, true); player.playVideo(); };

        function pantau() {
            if (pemantau) return;
            pemantau = setInterval(() => {
                if (!player || !siap || typeof player.getCurrentTime !== 'function') return;
                if (akhir && player.getCurrentTime() >= akhir - MARGIN_AKHIR) lompatKeAwal();
            }, INTERVAL_PANTAU);
        }

        function gagalMuat() {
            gagal = true;
            hentikanPantau();
            header.classList.remove('video-aktif');
            try { player && player.destroy(); } catch (e) { /* abaikan */ }
            el.remove();   // header kembali ke desain lama
        }

        player = new window.YT.Player(tempat, {
            host: 'https://www.youtube-nocookie.com',   // tanpa cookie pelacak sebelum diputar
            videoId: id,
            playerVars: {
                autoplay: 1, mute: 1, controls: 0, disablekb: 1, fs: 0, rel: 0,
                modestbranding: 1, iv_load_policy: 3, playsinline: 1,
                start: mulai, origin: window.location.origin,
            },
            events: {
                onReady: (e) => {
                    siap = true;
                    e.target.mute();
                    if (terlihat && !document.hidden) e.target.playVideo();
                },
                onStateChange: (e) => {
                    const S = window.YT.PlayerState;
                    if (e.data === S.PLAYING) {
                        el.classList.add('is-playing');
                        header.classList.add('video-aktif');
                        pantau();
                    } else if (e.data === S.ENDED) {
                        lompatKeAwal();   // jaring pengaman bila video habis sebelum batas akhir
                    } else if (e.data === S.PAUSED) {
                        hentikanPantau();
                    }
                },
                onError: gagalMuat,   // 2/5/100/101/150: video tidak ada / tidak boleh di-embed
            },
        });

        // Pause saat header keluar layar / Pause when the header leaves the viewport
        if ('IntersectionObserver' in window) {
            new IntersectionObserver((entries) => {
                terlihat = entries[0].isIntersecting;
                if (!siap || gagal) return;
                if (terlihat && !document.hidden) player.playVideo(); else player.pauseVideo();
            }, { threshold: 0.05 }).observe(header);
        }

        // Pause saat tab disembunyikan / Pause when the tab is hidden
        document.addEventListener('visibilitychange', () => {
            if (!siap || gagal) return;
            if (document.hidden) player.pauseVideo(); else if (terlihat) player.playVideo();
        });
    }

    // ---- Mulai setelah halaman selesai & browser santai / start after load + idle ----
    function mulaiSemua() {
        muatApi().then((ok) => {
            if (!ok) return wadah.forEach((el) => el.remove());
            wadah.forEach(pasang);
        });
    }
    const tunda = () => ('requestIdleCallback' in window)
        ? window.requestIdleCallback(mulaiSemua, { timeout: 2000 })
        : setTimeout(mulaiSemua, 800);

    if (document.readyState === 'complete') tunda();
    else window.addEventListener('load', tunda, { once: true });
})();
