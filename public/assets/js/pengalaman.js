/* Form "Bagikan Pengalaman" (F06): dropzone klik/seret, validasi tipe & ukuran, pratinjau, hapus foto,
   progres unggah, kirim via XHR + CSRF, lalu tambahkan slide baru (HTML dari server) ke carousel.
   Tanpa JS, form tetap bisa dikirim sebagai form biasa (progressive enhancement). */
document.addEventListener('DOMContentLoaded', () => {
    const $ = (id) => document.getElementById(id);
    const form = $('formPengalaman');
    if (!form) return;

    const modalEl = $('modalUnggahPengalaman');
    const dropzone = $('uploadDropzone');
    const fileInput = $('inputFotoPengalaman');
    const preview = $('filePreview');
    const previewBg = $('filePreviewBg');
    const galat = $('pengalamanGalat');
    const BATAS = 5 * 1024 * 1024;
    const TIPE = ['image/jpeg', 'image/png', 'image/webp'];

    // Setelah gagal validasi (form biasa), modal dibuka lagi otomatis agar galat terlihat
    if (modalEl.hasAttribute('data-buka-otomatis')) bootstrap.Modal.getOrCreateInstance(modalEl).show();

    const tampilGalat = (pesan) => {
        galat.replaceChildren(...[].concat(pesan).map((t) => Object.assign(document.createElement('div'), { textContent: t })));
        galat.classList.remove('d-none');
    };
    const bersihGalat = () => { galat.classList.add('d-none'); galat.replaceChildren(); };

    /* ---------- dropzone ---------- */
    const pilihFile = () => fileInput.click();
    dropzone.addEventListener('click', pilihFile);
    dropzone.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); pilihFile(); } });
    ['dragenter', 'dragover'].forEach((ev) => dropzone.addEventListener(ev, (e) => { e.preventDefault(); dropzone.classList.add('dragover'); }));
    ['dragleave', 'drop'].forEach((ev) => dropzone.addEventListener(ev, (e) => { e.preventDefault(); dropzone.classList.remove('dragover'); }));
    dropzone.addEventListener('drop', (e) => {
        const file = e.dataTransfer.files && e.dataTransfer.files[0];
        if (file) { fileInput.files = e.dataTransfer.files; tampilkanPreview(file); }
    });
    fileInput.addEventListener('change', () => { if (fileInput.files[0]) tampilkanPreview(fileInput.files[0]); });

    function resetFoto() {
        fileInput.value = '';
        preview.classList.add('d-none');
        dropzone.classList.remove('d-none');
        previewBg.style.backgroundImage = '';
    }

    function tampilkanPreview(file) {
        if (!TIPE.includes(file.type)) { tampilGalat('File harus berupa gambar (JPG, PNG, WEBP).'); resetFoto(); return; }
        if (file.size > BATAS) { tampilGalat('Ukuran file maksimal 5MB.'); resetFoto(); return; }
        bersihGalat();
        const reader = new FileReader();
        reader.onload = (e) => {
            previewBg.style.backgroundImage = `url('${e.target.result}')`;
            $('fileName').textContent = file.name;
            $('fileSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
            preview.classList.remove('d-none');
            dropzone.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    }
    $('removeFileBtn').addEventListener('click', (e) => { e.stopPropagation(); resetFoto(); });

    /* ---------- kirim ---------- */
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        bersihGalat();
        if (!form.reportValidity()) return;
        if (!fileInput.files[0]) { tampilGalat('Foto wajib diunggah.'); return; }

        const tombol = $('submitBtn');
        const wrap = $('uploadProgress'), bar = $('uploadProgressBar'), teks = $('uploadStatusText');
        tombol.disabled = true;
        wrap.classList.remove('d-none');
        bar.style.width = '0%';
        teks.textContent = 'Mengunggah foto...';

        const selesai = () => { tombol.disabled = false; setTimeout(() => { wrap.classList.add('d-none'); bar.style.width = '0%'; }, 400); };

        const xhr = new XMLHttpRequest();
        xhr.open('POST', form.action);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        xhr.upload.onprogress = (ev) => {
            if (ev.lengthComputable) bar.style.width = Math.round((ev.loaded / ev.total) * 90) + '%';
        };
        xhr.onload = () => {
            let data = {};
            try { data = JSON.parse(xhr.responseText); } catch (_) { /* biarkan kosong */ }

            if (xhr.status === 201) {
                bar.style.width = '100%';
                teks.textContent = 'Selesai!';
                tambahSlide(data.html);
                form.reset();
                resetFoto();
                selesai();
                bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                return;
            }
            if (xhr.status === 422 && data.errors) tampilGalat(Object.values(data.errors).flat());
            else if (xhr.status === 429) tampilGalat('Terlalu banyak kiriman. Tunggu sebentar lalu coba lagi.');
            else if (xhr.status === 419) tampilGalat('Sesi halaman sudah habis. Muat ulang halaman lalu coba lagi.');
            else tampilGalat('Gagal mengunggah pengalaman. Coba lagi.');
            selesai();
        };
        xhr.onerror = () => { tampilGalat('Koneksi terputus. Periksa internetmu lalu coba lagi.'); selesai(); };
        xhr.send(new FormData(form));   // sudah memuat _token dan semua field
    });

    /* ---------- slide baru ---------- */
    function tambahSlide(html) {
        if (!html) return;
        const inner = $('carouselPengalamanInner');
        inner.querySelector('[data-kosong]')?.remove();                     // pesan "belum ada" tidak dibutuhkan lagi
        inner.querySelectorAll('.carousel-item').forEach((el) => el.classList.remove('active'));

        const wadah = document.createElement('div');
        wadah.innerHTML = html.trim();                                      // HTML sudah di-escape oleh Blade di server
        const slide = wadah.firstElementChild;
        slide.classList.add('active');
        inner.appendChild(slide);

        const kartu = slide.querySelector('.experience-card[data-foto]');
        if (kartu && window.deteksiKecerahan) window.deteksiKecerahan(kartu);
        bootstrap.Carousel.getOrCreateInstance($('carouselPengalaman')).to(inner.children.length - 1);
    }
});
