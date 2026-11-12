/* Deteksi kecerahan foto latar kartu pengalaman supaya teks kutipan tetap terbaca (F05).
   Kartu .experience-card[data-foto] mulai dengan kelas is-dark; di sini diganti is-dark / is-light.
   Foto sekarang satu domain (/storage/...), jadi canvas tidak terkena batasan CORS.
   Dibuka ke window.deteksiKecerahan agar pengalaman.js bisa memanggilnya untuk slide baru. */
(function () {
    function hitung(url) {
        return new Promise((resolve) => {
            const img = new Image();
            img.crossOrigin = 'Anonymous';
            img.onload = () => {
                try {
                    const canvas = document.createElement('canvas');
                    canvas.width = 40; canvas.height = 40;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, 40, 40);
                    const data = ctx.getImageData(0, 0, 40, 40).data;
                    let total = 0, jumlah = 0;
                    for (let i = 0; i < data.length; i += 4) {
                        total += 0.2126 * data[i] + 0.7152 * data[i + 1] + 0.0722 * data[i + 2];
                        jumlah++;
                    }
                    resolve(total / jumlah < 140 ? 'is-dark' : 'is-light');
                } catch (e) { resolve('is-dark'); }   // canvas "tainted" -> aman: anggap gelap
            };
            img.onerror = () => resolve('is-dark');
            img.src = url;
        });
    }

    async function terapkan(kartu) {
        if (!kartu.dataset.foto) return;
        const kelas = await hitung(kartu.dataset.foto);
        kartu.classList.remove('is-dark', 'is-light');
        kartu.classList.add(kelas);
    }

    window.deteksiKecerahan = terapkan;
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.experience-card[data-foto]').forEach(terapkan);
    });
})();
