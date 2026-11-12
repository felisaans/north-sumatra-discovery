/* Avatar Gravatar -> lingkaran inisial bila pengguna belum punya foto di Gravatar.
   Gravatar dipanggil dengan ?d=404, jadi tanpa foto browser menerima 404 dan memicu event "error".
   Markup datang dari resources/views/components/avatar.blade.php (atribut data-inisial dll).
   Pengganti window.__avatarGagal di ui.js lama. */
(function () {
    function gantiDenganInisial(img) {
        if (!img.dataset || !img.dataset.inisial) return;
        const ukuran = parseInt(img.dataset.avatarSize, 10) || 40;
        const div = document.createElement('div');
        div.className = ('avatar-initial ' + (img.dataset.avatarClass || '')).trim();
        div.style.width = ukuran + 'px';
        div.style.height = ukuran + 'px';
        div.style.fontSize = Math.round(ukuran * 0.38) + 'px';
        div.textContent = img.dataset.inisial;   // textContent: aman dari HTML injection
        img.replaceWith(div);
    }

    // Event "error" pada <img> tidak menggelembung, jadi didengar di fase capture.
    // Ini juga menangkap avatar yang ditambahkan JS belakangan (slide pengalaman baru).
    document.addEventListener('error', (e) => {
        if (e.target instanceof HTMLImageElement && e.target.matches('img[data-inisial]')) {
            gantiDenganInisial(e.target);
        }
    }, true);

    // Gambar yang SUDAH gagal sebelum skrip ini jalan tidak memicu event lagi -> periksa manual
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('img[data-inisial]').forEach((img) => {
            if (img.complete && img.naturalWidth === 0) gantiDenganInisial(img);
        });
    });
})();
