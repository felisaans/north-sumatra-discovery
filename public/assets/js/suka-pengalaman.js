/* Tombol suka (like) pada slide "Pengalaman Pengunjung" di Beranda.
   - Satu pendengar klik di document (delegasi) -> slide baru yang ditambahkan pengalaman.js otomatis ikut bekerja.
   - Belum login: server membalas 401 -> pengguna diarahkan ke halaman masuk (lalu kembali ke Beranda).
   - Tanpa JS tombol tidak berfungsi (hanya jumlah suka yang terbaca). */
document.addEventListener('click', async (e) => {
    const tombol = e.target.closest('.experience-like');
    if (!tombol) return;
    e.preventDefault();
    if (tombol.dataset.sibuk) return;            // cegah klik ganda selagi menunggu server
    tombol.dataset.sibuk = '1';

    try {
        const res = await fetch(tombol.dataset.sukaUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
        });

        if (res.status === 401) { window.location.href = tombol.dataset.masukUrl; return; }
        if (res.status === 419) { window.location.reload(); return; }      // sesi habis
        if (!res.ok) throw new Error('gagal');

        const data = await res.json();
        const ikon = tombol.querySelector('i');
        tombol.classList.toggle('is-liked', data.suka);
        tombol.setAttribute('aria-pressed', data.suka ? 'true' : 'false');
        ikon.classList.toggle('fa-solid', data.suka);
        ikon.classList.toggle('fa-regular', !data.suka);
        tombol.querySelector('.experience-like-jumlah').textContent = data.jumlah;
    } catch (_) {
        tombol.classList.add('is-gagal');
        setTimeout(() => tombol.classList.remove('is-gagal'), 700);
    } finally {
        delete tombol.dataset.sibuk;
    }
});
