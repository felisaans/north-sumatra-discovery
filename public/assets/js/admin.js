/* Dashboard Admin: satu modal form untuk semua modul (setara admin.js lama, tapi data & validasi di server).
   - Tambah : form dikosongkan, action = POST ke route store (data-store-url pada #modalForm)
   - Ubah   : form diisi dari data-record tombol, action = PUT ke route update (data-url tombol)
   - Gagal validasi (server): halaman dimuat ulang dengan old() input, modal dibuka lagi (data-buka-otomatis)
   - Hapus  : konfirmasi dulu, lalu kirim form DELETE
   - Gambar : pratinjau langsung saat URL gambar diketik/ditempel */
document.addEventListener('DOMContentLoaded', () => {
    const $ = (id) => document.getElementById(id);
    const modalEl = $('modalForm');
    const form = $('formResource');
    if (!modalEl || !form) return;

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    const PLACEHOLDER = 'https://placehold.co/600x600/F8E8D0/54205F?text=North+Sumatra+Discovery';
    const label = modalEl.dataset.label;

    function atur({ url, editId }) {
        form.action = url;
        $('inputMethod').disabled = !editId;            // _method=PUT hanya saat mengubah
        $('inputEditId').value = editId || '';
        $('judulModal').textContent = (editId ? 'Ubah ' : 'Tambah ') + label;
    }

    function isi(record) {
        // hanya field di badan modal; _token, _method, _edit_id tidak disentuh
        form.querySelectorAll('.modal-body [name]').forEach((el) => {
            const v = record[el.name];
            el.value = v === null || v === undefined ? '' : v;
        });
        segarkanPratinjau();
    }

    function segarkanPratinjau() {
        form.querySelectorAll('[data-preview-input]').forEach((input) => {
            const img = form.querySelector(`[data-preview-for="${input.name}"]`);
            img.style.visibility = 'visible';
            img.src = input.value.trim() || PLACEHOLDER;
        });
    }
    form.querySelectorAll('[data-preview-input]').forEach((input) => input.addEventListener('input', segarkanPratinjau));

    function bukaBaru(opsi, record) {
        $('pesanForm').replaceChildren();               // galat lama tidak ikut terbawa
        atur(opsi);
        isi(record);
        modal.show();
    }

    // Tambah Data
    $('btnTambah')?.addEventListener('click', () => bukaBaru({ url: modalEl.dataset.storeUrl, editId: '' }, {}));

    // Ubah
    document.querySelectorAll('[data-aksi="edit"]').forEach((tombol) => {
        tombol.addEventListener('click', () => {
            bukaBaru({ url: tombol.dataset.url, editId: tombol.dataset.id }, JSON.parse(tombol.dataset.record));
        });
    });

    // Hapus (konfirmasi)
    document.querySelectorAll('form.js-hapus').forEach((f) => {
        f.addEventListener('submit', (e) => {
            if (!confirm('Hapus data ini? Tindakan tidak bisa dibatalkan.')) e.preventDefault();
        });
    });

    // Cegah klik ganda saat menyimpan
    form.addEventListener('submit', () => {
        const tombol = $('btnSimpanForm');
        setTimeout(() => { tombol.disabled = true; tombol.textContent = 'Menyimpan...'; }, 0);
    });

    // Buka lagi setelah gagal validasi: nilai field sudah diisi server dari old(), jangan ditimpa
    if (modalEl.hasAttribute('data-buka-otomatis')) {
        const editId = modalEl.dataset.editId;
        if (editId && modalEl.dataset.editUrl) atur({ url: modalEl.dataset.editUrl, editId });
        segarkanPratinjau();
        modal.show();
    }
});
