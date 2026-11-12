/* Pesan flash (mis. "Ulasanmu sudah disimpan.") hilang sendiri setelah beberapa detik.
   Durasi diambil dari atribut data-autohide (milidetik) di partials/flash.blade.php. */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.flash-stack .alert[data-autohide]').forEach((el) => {
        const durasi = parseInt(el.dataset.autohide, 10) || 5000;
        setTimeout(() => {
            // lewat Bootstrap supaya animasi fade berjalan; bila belum termuat, cukup sembunyikan
            if (window.bootstrap) bootstrap.Alert.getOrCreateInstance(el).close();
            else el.remove();
        }, durasi);
    });
});
