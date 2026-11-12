# North Sumatra Discovery --- Color Palette Guide

## Ulos Electric · Balanced

Dokumen ini menjadi **sumber acuan warna** untuk menyelaraskan seluruh
stylesheet North Sumatra Discovery, termasuk `:root` dan `dark.css`.

Tujuannya adalah menerapkan identitas visual **Ulos Electric ---
Balanced** tanpa membuat mode terang dan mode gelap saling bertabrakan.

------------------------------------------------------------------------

## 1. Arah visual

**Karakter:** contemporary travel editorial with cultural energy.

Palet ini mengambil inspirasi dari kontras warna tekstil ulos, lalu
dibuat lebih modern dan seimbang: - Ungu menjadi identitas utama. -
Berry pink memberi karakter budaya dan aksen sekunder. - Vermilion
memberi energi dan kehangatan. - Golden sun digunakan sebagai
highlight. - Warm linen menjadi penyeimbang agar tampilan tidak terlalu
gelap atau mencolok.

Jangan gunakan semua warna dengan intensitas yang sama. Warna aksen
harus muncul secara terarah.

## 2. Warna inti / brand colors

  -----------------------------------------------------------------------
  Nama                    HEX                     Peran
  ----------------------- ----------------------- -----------------------
  Ulos Purple             `#54205F`               Primary brand, tombol
                                                  utama, identitas

  Berry Pink              `#B72D63`               Secondary brand, link,
                                                  label, aksen

  Vermilion               `#F04438`               Aksen hangat, favorit,
                                                  highlight

  Golden Sun              `#FFB52E`               Rating, highlight,
                                                  aksen cerah

  Warm Linen              `#F8E8D0`               Background utama mode
                                                  terang, teks pada
                                                  permukaan gelap

  Ink Black               `#29212B`               Teks utama, footer,
                                                  permukaan tergelap
  -----------------------------------------------------------------------

### Turunan warna yang disetujui

  -----------------------------------------------------------------------
  Nama turunan            HEX                     Penggunaan
  ----------------------- ----------------------- -----------------------
  Purple Lift             `#71317D`               Hover primary,
                                                  permukaan ungu yang
                                                  lebih terang

  Purple Deep             `#3F1748`               Active / pressed state

  Berry Deep              `#92234E`               Hover secondary

  Berry Blush             `#F4C5D5`               Aksen pink lembut

  Blush Surface           `#F9E5EC`               Background section
                                                  bernuansa pink

  Soft Ivory              `#FFF9F1`               Background section
                                                  terang

  Pale Gold               `#FFF1CF`               Background section
                                                  highlight

  Warm Border             `#EAD9D5`               Border card pada mode
                                                  terang

  Muted Text              `#716670`               Teks sekunder pada mode
                                                  terang
  -----------------------------------------------------------------------

Gunakan turunan ini sebagai bagian dari keluarga warna yang sama. Jangan
membuat warna brand baru tanpa alasan dan tanpa memperbarui dokumen ini.

------------------------------------------------------------------------

## 3. Token warna mode terang (light theme)

  Fungsi                    Warna
  ------------------------- ---------------------------
  Page background           `#F8E8D0`
  Alternate section         `#FFF9F1`
  Pink section              `#F9E5EC`
  Yellow section            `#FFF1CF`
  Dark / identity section   `#54205F`
  Footer                    `#29212B`
  Card surface              `#FFFFFF`
  Card border               `#EAD9D5`
  Main heading              `#29212B`
  Body text                 `#514852`
  Muted text                `#716670`
  Link                      `#B72D63`
  Link hover                `#54205F`
  Input surface             `rgba(84, 32, 95, 0.07)`
  Focus ring                `rgba(183, 45, 99, 0.22)`

### Button --- light theme

  State                 Background                 Text
  --------------------- -------------------------- -----------
  Primary default       `#54205F`                  `#FFFFFF`
  Primary hover         `#71317D`                  `#FFFFFF`
  Primary active        `#3F1748`                  `#FFFFFF`
  Secondary default     `#B72D63`                  `#FFFFFF`
  Secondary hover       `#92234E`                  `#FFFFFF`
  Accent default        `#FFB52E`                  `#29212B`
  Outline default       `rgba(84, 32, 95, 0.07)`   `#54205F`
  Disabled background   `#E8DDE5`                  `#958A94`

Catatan kontras: Vermilion `#F04438` sebaiknya digunakan untuk aksen,
ikon, atau bidang dekoratif. Untuk teks kecil di atas Vermilion, gunakan
teks gelap `#29212B`, bukan putih.

------------------------------------------------------------------------

## 4. Token warna mode gelap (dark theme)

Mode gelap **bukan sekadar membalik warna light theme**. Buat permukaan
gelap yang berlapis, pertahankan identitas ungu, dan gunakan warna
terang untuk teks.

Gunakan spesifikasi berikut sebagai arah implementasi:

  Fungsi                  Warna dark theme
  ----------------------- -----------------------------
  Page background         `#17131A`
  Alternate section       `#201923`
  Elevated section        `#29212B`
  Card surface            `#2F2633`
  Card hover              `#3A2B40`
  Card border             `rgba(248, 232, 208, 0.12)`
  Main heading            `#FFF9F1`
  Body text               `#E6DCE5`
  Muted text              `#B7AAB8`
  Primary brand surface   `#71317D`
  Primary hover           `#8A4395`
  Secondary / berry       `#E15A8A`
  Soft pink accent        `#F4C5D5`
  Coral accent            `#FF746B`
  Golden accent           `#FFCA61`
  Input surface           `rgba(248, 232, 208, 0.08)`
  Focus ring              `rgba(244, 197, 213, 0.55)`
  Footer                  `#110E14`

### Button --- dark theme

  State               Background                    Text
  ------------------- ----------------------------- -----------
  Primary default     `#71317D`                     `#FFFFFF`
  Primary hover       `#8A4395`                     `#FFFFFF`
  Primary active      `#54205F`                     `#FFFFFF`
  Secondary default   `#B72D63`                     `#FFFFFF`
  Secondary hover     `#D14378`                     `#FFFFFF`
  Accent default      `#FFCA61`                     `#29212B`
  Outline default     `rgba(248, 232, 208, 0.08)`   `#F8E8D0`

Warna di tabel dark theme adalah rekomendasi token khusus dark mode.
Jangan memaksakan nilai light theme ke permukaan gelap jika kontrasnya
buruk.

------------------------------------------------------------------------

## 5. Warna semantik

Warna semantik digunakan untuk menyampaikan makna, bukan sekadar
dekorasi.

  Fungsi                      Light theme   Dark theme
  --------------------------- ------------- ------------
  Success / lokasi berhasil   `#23845B`     `#55B98A`
  Warning / rating            `#D99513`     `#FFCA61`
  Danger / error / favorit    `#D93645`     `#FF746B`
  Info                        `#54205F`     `#C99AD2`
  Rating star                 `#FFB52E`     `#FFCA61`
  Favorite heart              `#F04438`     `#FF746B`
  Location pin                `#23845B`     `#55B98A`

Jangan mengubah warna success menjadi ungu atau warna rating menjadi
pink. Pertahankan makna visual tiap status.

------------------------------------------------------------------------

## 6. Decorative colors dan effects

  Elemen                            Nilai
  --------------------------------- -----------------------------
  Pattern dots (light)              `rgba(183, 45, 99, 0.14)`
  Pattern dots (dark)               `rgba(244, 197, 213, 0.12)`
  Purple shadow                     `rgba(84, 32, 95, 0.12)`
  Dark shadow                       `rgba(17, 14, 20, 0.28)`
  Coral glow                        `rgba(240, 68, 56, 0.24)`
  Gold glow                         `rgba(255, 181, 46, 0.25)`
  Glass background (light)          `rgba(248, 232, 208, 0.30)`
  Glass background strong (light)   `rgba(248, 232, 208, 0.50)`
  Glass edge (light)                `rgba(255, 255, 255, 0.62)`
  Glass background (dark)           `rgba(41, 33, 43, 0.58)`
  Glass background strong (dark)    `rgba(41, 33, 43, 0.78)`
  Glass edge (dark)                 `rgba(248, 232, 208, 0.16)`

Pertahankan efek glass yang subtle. Jangan membuat semua panel
transparan atau terlalu blur.

------------------------------------------------------------------------

## 7. Proporsi penggunaan warna

Panduan proporsi visual untuk keseluruhan halaman:

  Kelompok                                     Proporsi target
  ------------------------------------------ -----------------
  Neutral backgrounds (Warm Linen / ivory)                 55%
  White / light surfaces                                   20%
  Ulos Purple                                              15%
  Berry Pink dan Vermilion                                  7%
  Golden Sun                                                3%

Ini panduan visual, bukan aturan CSS yang harus dihitung secara literal.

### Aturan praktis

1.  Purple adalah identitas utama, bukan warna untuk memenuhi seluruh
    halaman.
2.  Gunakan Berry Pink pada link, label, badge, dan aksen sekunder.
3.  Gunakan Vermilion secara hemat untuk elemen yang perlu menarik
    perhatian.
4.  Golden Sun paling efektif sebagai highlight kecil, rating, atau CTA
    khusus.
5.  Gunakan Warm Linen dan ivory untuk memberi ruang visual.
6.  Foto destinasi, kuliner, dan budaya harus tetap menjadi fokus utama
    card.
7.  Motif geometris terinspirasi ulos boleh digunakan pada hero, banner,
    atau footer, tetapi jangan menjadi background setiap section.

------------------------------------------------------------------------

## 8. Arahan implementasi untuk Claude

Gunakan dokumen ini sebagai **color specification**, bukan instruksi
untuk mengganti seluruh CSS secara membabi buta.

-   Pertahankan semua nama variabel CSS yang sudah digunakan halaman,
    termasuk alias lama seperti `--c-mint`, `--c-night`,
    `--c-porcelain`, `--color-teal`, `--color-accent`, `--btn-bg`,
    `--rgb-teal`, dan variabel Bootstrap.
-   Jangan menghapus, mengganti nama, atau mengubah selector, struktur
    komponen, layout, spacing, typography, radius, maupun animasi yang
    tidak berkaitan dengan warna.
-   Audit `:root`, `dark.css`, dan override tema. Pastikan setiap token
    memiliki nilai yang tepat untuk light dan dark theme.
-   Hindari deklarasi yang saling menimpa atau selector tema yang
    memiliki prioritas tidak konsisten.
-   Pastikan `data-theme="dark"` dan mekanisme theme toggle yang sudah
    ada tetap berfungsi.
-   Periksa komponen navbar, button, region chip, footer, form, input,
    nav-pills, badge, card, glass effect, focus state, hover, dan
    disabled state di kedua tema.
-   Jangan menerapkan warna light theme ke dark theme secara otomatis.
    Gunakan tabel dark theme di dokumen ini.
-   Jangan mengubah nilai radius atau font hanya karena sedang
    mengerjakan palet warna.
-   Jika ada variabel lama yang namanya tidak sesuai dengan warna
    barunya (misalnya `--color-teal`), **pertahankan nama variabelnya**
    dan ubah nilai tokennya. Nama variabel lama merupakan kontrak
    kompatibilitas dengan stylesheet halaman.
-   Jangan menambahkan warna baru di luar spesifikasi tanpa alasan yang
    jelas.
-   Setelah perubahan, laporkan file yang diubah dan token yang
    diselaraskan. Jangan mengklaim sudah menguji tampilan browser jika
    belum benar-benar menjalankan aplikasi.

**Hasil yang diharapkan:** satu sistem warna Ulos Electric yang
konsisten di seluruh halaman North Sumatra Discovery, dengan light theme
dan dark theme yang sama-sama terasa sengaja dirancang, bukan saling
bertabrakan.
