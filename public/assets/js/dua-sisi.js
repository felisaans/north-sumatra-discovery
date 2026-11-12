/* North Sumatra Discovery: ilustrasi "dua sisi" (kota & kampung).
   1) [data-scene] -> isi elemen dengan ilustrasi SVG (pengganti foto sementara; foto asli di atasnya tetap menutup).
   2) #duaSisi -> slider geser kota <-> kampung di hero (sekali menyapu saat dibuka, lalu bisa digeser / panah keyboard). */
(function () {
    var n = 0;
    function roof(x, y, s, f) {
        return '<path d="M' + x + ' ' + y + 'c' + s * .15 + ' ' + -s * .1 + ' ' + s * .2 + ' ' + -s * .45 + ' ' + s * .28 + ' ' + -s * .7 + 'c' + s * .1 + ' ' + s * .3 + ' ' + s * .34 + ' ' + s * .3 + ' ' + s * .44 + ' 0c' + s * .08 + ' ' + s * .25 + ' ' + s * .13 + ' ' + s * .6 + ' ' + s * .28 + ' ' + s * .7 + 'z" fill="' + f + '"/><rect x="' + (x + s * .12) + '" y="' + y + '" width="' + s * .76 + '" height="' + s * .22 + '" fill="' + f + '"/>';
    }
    function scene(k, w, h, seed) {
        var s = seed || 1, r = function () { return (s = (s * 9301 + 49297) % 233280) / 233280; }, id = 'dsg' + n++;
        var P = {
            city: ['#FFF1CF', '#F9E5EC', '#F4C5D5', '#B72D63', '#29212B'], lake: ['#FFF9F1', '#F4C5D5', '#71317D', '#54205F', '#29212B'],
            hill: ['#FFF1CF', '#F8E8D0', '#F4C5D5', '#B72D63', '#3F1748'], jungle: ['#F9E5EC', '#F4C5D5', '#92234E', '#B72D63', '#3F1748']
        }[k] || null;
        if (!P) return '';
        var gy = h * .78;
        var o = '<svg viewBox="0 0 ' + w + ' ' + h + '" preserveAspectRatio="xMidYMid slice" aria-hidden="true" focusable="false"><defs><linearGradient id="' + id + '" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="' + P[0] + '"/><stop offset="1" stop-color="' + P[1] + '"/></linearGradient></defs><rect width="' + w + '" height="' + h + '" fill="url(#' + id + ')"/>';
        if (k === 'lake' || k === 'hill') o += '<circle cx="' + w * .78 + '" cy="' + h * .26 + '" r="' + w * .07 + '" fill="#FFB52E"/>';
        if (k === 'city') {
            for (var x = -10; x < w;) {
                var bw = w * (.05 + r() * .06), bh = h * (.16 + r() * .36);
                o += '<rect x="' + x + '" y="' + (gy - bh) + '" width="' + bw + '" height="' + (bh + 2) + '" fill="' + P[r() > .5 ? 2 : 3] + '"/>';
                if (r() > .35) for (var wy = gy - bh + h * .03; wy < gy - h * .03; wy += h * .055) for (var wx = x + bw * .18; wx < x + bw * .8; wx += bw * .3) if (r() > .5) o += '<rect x="' + wx + '" y="' + wy + '" width="' + bw * .1 + '" height="' + h * .022 + '" fill="#FFB52E" opacity=".9"/>';
                if (r() > .55) o += '<rect x="' + (x + bw * .45) + '" y="' + (gy - bh - h * .07) + '" width="' + bw * .1 + '" height="' + h * .07 + '" fill="' + P[3] + '"/>';
                x += bw + w * .008;
            }
        } else if (k === 'lake') {
            o += '<path d="M0 ' + gy + 'L' + w * .18 + ' ' + h * .44 + 'L' + w * .36 + ' ' + h * .62 + 'L' + w * .6 + ' ' + h * .36 + 'L' + w * .82 + ' ' + h * .6 + 'L' + w + ' ' + h * .46 + 'V' + gy + 'z" fill="' + P[2] + '"/><rect y="' + h * .64 + '" width="' + w + '" height="' + (gy - h * .64) + '" fill="' + P[1] + '" opacity=".7"/><path d="M' + w * .2 + ' ' + gy + 'Q' + w * .5 + ' ' + h * .6 + ' ' + w * .8 + ' ' + gy + 'z" fill="' + P[3] + '"/>';
            [.38, .5, .6].forEach(function (p, i) { o += roof(w * p, gy - h * .02 - (i % 2) * h * .03, w * .09, P[4]); });
        } else if (k === 'hill') {
            [[.5, 2], [.62, 3], [.7, 4]].forEach(function (a) { o += '<path d="M0 ' + h * a[0] + 'Q' + w * .3 + ' ' + h * (a[0] - .16) + ' ' + w * .55 + ' ' + h * (a[0] - .02) + 'T' + w + ' ' + h * (a[0] - .08) + 'V' + gy + 'H0z" fill="' + P[a[1]] + '"/>'; });
            o += roof(w * .58, h * .7, w * .1, P[4]);
        } else if (k === 'jungle') {
            for (var i = 0; i < 26; i++) o += '<circle cx="' + r() * w + '" cy="' + h * (.38 + r() * .36) + '" r="' + w * (.05 + r() * .05) + '" fill="' + P[2 + i % 3] + '"/>';
            o += '<path d="M' + w * .45 + ' ' + gy + 'Q' + w * .55 + ' ' + h * .6 + ' ' + w * .5 + ' ' + h * .45 + '" stroke="' + P[0] + '" stroke-width="' + w * .03 + '" fill="none" opacity=".8"/>';
        }
        return o + '<rect y="' + gy + '" width="' + w + '" height="' + (h - gy) + '" fill="' + P[4] + '"/></svg>';
    }
    document.querySelectorAll('[data-scene]').forEach(function (el) {
        el.insertAdjacentHTML('afterbegin', scene(el.dataset.scene, 800, 800, +el.dataset.seed || 1));
    });

    var box = document.getElementById('duaSisi');
    var vil = document.getElementById('dsKampung'), grip = document.getElementById('dsGrip');
    if (!box || !vil || !grip) return;
    var X = 50, drag = false, intro = true;
    function setX(v) {
        X = Math.max(4, Math.min(96, v));
        vil.style.clipPath = 'inset(0 0 0 ' + X + '%)';
        grip.style.left = X + '%';
        grip.setAttribute('aria-valuenow', Math.round(X));
    }
    function at(e) { var b = box.getBoundingClientRect(); setX((e.clientX - b.left) / b.width * 100); }
    box.addEventListener('pointerdown', function (e) { drag = true; intro = false; at(e); box.setPointerCapture(e.pointerId); });
    box.addEventListener('pointermove', function (e) { if (drag) at(e); });
    ['pointerup', 'pointercancel'].forEach(function (t) { box.addEventListener(t, function () { drag = false; }); });
    grip.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowLeft') { intro = false; setX(X - 5); e.preventDefault(); }
        if (e.key === 'ArrowRight') { intro = false; setX(X + 5); e.preventDefault(); }
    });
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) { setX(50); return; }
    setX(96);
    var t0 = performance.now();
    (function f(t) {
        if (!intro) return;
        var p = Math.min(1, (t - t0 - 500) / 1800);
        if (p > 0) setX(96 - 46 * (1 - Math.pow(1 - p, 3)));
        if (p < 1) requestAnimationFrame(f);
    })(t0);
})();
