<?php

/*
 | Pengaturan situs yang bisa diubah lewat .env tanpa menyentuh kode.
 | Ikon media sosial di footer HANYA tampil bila URL-nya diisi, supaya tidak ada tautan mati.
 | Contoh di .env:  SOSIAL_INSTAGRAM=https://instagram.com/acehdiscovery   ATAU cukup   SOSIAL_INSTAGRAM=acehdiscovery
 */
return [
    'sosial' => [
        'instagram' => ['ikon' => 'fa-instagram', 'nama' => 'Instagram', 'url' => env('SOSIAL_INSTAGRAM')],
        'youtube' => ['ikon' => 'fa-youtube', 'nama' => 'YouTube', 'url' => env('SOSIAL_YOUTUBE')],
        'tiktok' => ['ikon' => 'fa-tiktok', 'nama' => 'TikTok', 'url' => env('SOSIAL_TIKTOK')],
        'facebook' => ['ikon' => 'fa-facebook', 'nama' => 'Facebook', 'url' => env('SOSIAL_FACEBOOK')],
    ],
];
