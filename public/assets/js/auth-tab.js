/* Tab Masuk / Daftar di halaman /masuk (F13). Dua form terpisah (masing-masing dikirim ke server,
   validasi di server); skrip ini hanya menukar form yang tampil dan merapikan pesan galat. */
document.addEventListener('DOMContentLoaded', () => {
    const tombol = document.querySelectorAll('#tabAuth [data-mode]');
    const panel = document.querySelectorAll('form[data-panel]');
    tombol.forEach((b) => b.addEventListener('click', () => {
        tombol.forEach((x) => x.classList.toggle('active', x === b));
        panel.forEach((p) => {
            const tampil = p.dataset.panel === b.dataset.mode;
            p.classList.toggle('d-none', !tampil);
            if (tampil) p.querySelector('input')?.focus();
        });
        // pesan galat milik tab sebelumnya dikosongkan agar tidak membingungkan
        document.querySelectorAll('form[data-panel] [role="alert"]').forEach((a) => { if (a.closest('form').classList.contains('d-none')) a.replaceChildren(); });
    }));
});
