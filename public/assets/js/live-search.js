/* Live search & filter tanpa reload (F07, F11, F12). Progressive enhancement:
   halaman tetap berfungsi sebagai GET biasa; skrip ini hanya mengambil potongan HTML daftar (?partial=1)
   dari route yang sama, lalu menaruhnya ke wadah.

   Form    : <form data-live-search data-target="#wadahDaftar" method="GET" action="...">
             - kolom teks   -> ditunda 300 ms setelah berhenti mengetik (debounce)
             - <select>     -> langsung saat berubah
             - tombol kirim -> langsung (tanpa reload)
   Dipakai juga oleh chip-filter.js lewat window.AcehLive.muat() untuk chip daerah Destinasi. */
(function () {
    const SPINNER = '<div class="text-center text-muted py-5"><span class="spinner-border spinner-border-sm me-2"></span>Memuat data...</div>';
    const GAGAL = '<p class="text-center text-danger py-5">Gagal memuat data. Coba muat ulang halaman.</p>';
    let pengendali = null;   // membatalkan permintaan lama bila pengguna mengetik lagi

    /** Ambil `url` (+ partial=1) dan taruh hasilnya di `wadah`. Alamat halaman ikut diperbarui tanpa menambah riwayat. */
    async function muat(url, wadah) {
        if (pengendali) pengendali.abort();
        pengendali = new AbortController();
        const sinyal = pengendali.signal;

        wadah.classList.add('sedang-memuat');
        const timerSpinner = setTimeout(() => { wadah.innerHTML = SPINNER; }, 400);   // spinner hanya bila lambat

        const ambil = new URL(url, location.href);
        ambil.searchParams.set('partial', '1');
        try {
            const res = await fetch(ambil, { signal: sinyal, headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const html = await res.text();
            wadah.innerHTML = html;
            const tampil = new URL(url, location.href);
            tampil.searchParams.delete('partial');
            history.replaceState(null, '', tampil);
        } catch (e) {
            if (e.name === 'AbortError') return;    // digantikan permintaan yang lebih baru
            console.error(e);
            wadah.innerHTML = GAGAL;
        } finally {
            clearTimeout(timerSpinner);
            if (pengendali && pengendali.signal === sinyal) wadah.classList.remove('sedang-memuat');
        }
    }
    window.AcehLive = { muat };

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form[data-live-search]').forEach((form) => {
            const wadah = document.querySelector(form.dataset.target);
            if (!wadah) return;

            const jalankan = () => {
                const params = new URLSearchParams(new FormData(form));
                [...params.keys()].forEach((k) => { if (params.get(k) === '') params.delete(k); });   // URL tetap bersih
                const query = params.toString();
                muat(form.action + (query ? '?' + query : ''), wadah);
            };

            let timer;
            form.querySelectorAll('input[type="text"], input[type="search"]').forEach((el) => {
                el.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(jalankan, 300); });
            });
            form.querySelectorAll('select').forEach((el) => el.addEventListener('change', jalankan));
            form.addEventListener('submit', (e) => { e.preventDefault(); clearTimeout(timer); jalankan(); });
        });
    });
})();
