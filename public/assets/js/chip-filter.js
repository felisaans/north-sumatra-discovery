/* Chip filter daerah di Beranda (F03). Disaring di klien tanpa memuat ulang.
   Markup:  <div data-chip-filter data-chip-target=".kuliner-populer-item"> ...tombol [data-filter-daerah]... </div>
   Item     :  elemen target ber-atribut data-daerah="<slug>".
   Mode remote (Destinasi, F12): <div data-chip-filter data-chip-remote="#wadah"> berisi tautan <a href="?daerah=slug">.
   Klik chip -> daftar diganti lewat window.AcehLive (live-search.js) tanpa reload; tanpa JS tautan tetap bekerja.
   Mode marquee: <div data-chip-filter data-chip-marquee="#idStrip"> -> kirim event "chip:filter" ke marquee.js.
   Aturan lama dipertahankan: "semua" menampilkan semua; item berdaerah "semua" (Seluruh Wilayah Sumatera Utara) selalu tampil. */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-chip-filter]').forEach((grup) => {
        const item = () => document.querySelectorAll(grup.dataset.chipTarget);
        grup.addEventListener('click', (e) => {
            const chip = e.target.closest('[data-filter-daerah]');
            if (!chip || !grup.contains(chip)) return;
            grup.querySelectorAll('[data-filter-daerah]').forEach((c) => c.classList.toggle('active', c === chip));

            // Mode marquee (Beranda): strip dibangun ulang oleh marquee.js, bukan disembunyikan satu per satu
            if (grup.dataset.chipMarquee) {
                document.dispatchEvent(new CustomEvent('chip:filter', { detail: { grup, daerah: chip.dataset.filterDaerah } }));
                return;
            }

            const remote = grup.dataset.chipRemote && document.querySelector(grup.dataset.chipRemote);
            if (remote && window.AcehLive && chip.href) {
                e.preventDefault();
                window.AcehLive.muat(chip.href, remote);
                return;
            }
            const target = chip.dataset.filterDaerah;
            item().forEach((el) => {
                const cocok = target === 'semua' || el.dataset.daerah === target || el.dataset.daerah === 'semua';
                el.style.display = cocok ? '' : 'none';
            });
        });
    });
});
