@extends('layouts.admin')
@section('judul', $modul['label'].' - Dashboard Admin - North Sumatra Discovery')

@php
    $moderasi = $modul['hanya_moderasi'] ?? false;

    // Nilai field yang bisa diubah, dikirim ke JS (admin.js) lewat atribut data-record saat tombol Ubah ditekan
    $rekam = function ($row) use ($modul) {
        $nilai = [];
        foreach ($modul['fields'] as $f) {
            $v = $row->{$f['name']};
            $nilai[$f['name']] = $v instanceof \DateTimeInterface ? $v->format('Y-m-d') : $v;
        }
        return $nilai;
    };
@endphp

@section('isi')
<div class="container-fluid">
    <div class="row">
        {{-- Sidebar: satu tautan per modul (config/admin.php) --}}
        <aside class="col-lg-2 admin-sidebar py-4">
            <nav class="nav flex-column gap-1">
                @foreach ($semuaModul as $k => $m)
                    <a href="{{ route("admin.$k.index") }}" @class(['nav-link d-flex align-items-center gap-2', 'active' => $k === $kunci])>
                        <i class="fa-solid {{ $m['icon'] }}"></i> {{ $m['label'] }}
                    </a>
                @endforeach
            </nav>
        </aside>

        <main class="col-lg-10 py-4 px-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-0 admin-judul">{{ $modul['label'] }}</h2>
                    <p class="text-muted small mb-0">
                        {{ $moderasi ? 'Kiriman publik dari beranda — kelola atau hapus di sini.' : 'Kelola data '.mb_strtolower($modul['label']).' yang tampil di halaman pengguna.' }}
                    </p>
                </div>
                @unless ($moderasi)
                    <button type="button" class="btn btn-aceh-primary px-4" id="btnTambah"><i class="fa-solid fa-plus me-2"></i>Tambah Data</button>
                @endunless
            </div>

            <form method="GET" action="{{ route("admin.$kunci.index") }}" class="d-flex gap-2 mb-3" role="search">
                <div class="input-group" style="max-width: 420px;">
                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="search" name="q" value="{{ $q }}" class="form-control" placeholder="Cari di {{ mb_strtolower($modul['label']) }}..." aria-label="Cari data">
                </div>
                <button type="submit" class="btn btn-aceh-primary px-3">Cari</button>
                @if ($q !== '')
                    <a href="{{ route("admin.$kunci.index") }}" class="btn btn-aceh-outline px-3">Reset</a>
                @endif
            </form>

            <div class="custom-card-bg p-3">
                <div class="table-responsive">
                    <table class="table align-middle mb-0" id="tabelData">
                        <thead>
                            <tr>
                                @foreach ($modul['columns'] as $col)
                                    <th>{{ $col['label'] ?? $col['key'] }}</th>
                                @endforeach
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($baris as $row)
                                <tr>
                                    @foreach ($modul['columns'] as $col)
                                        @php $jenis = $col['type'] ?? 'teks'; @endphp
                                        @if ($jenis === 'image')
                                            <td><x-gambar :src="$row->{$col['key'].'_url'}" alt="" kelas="thumb" /></td>
                                        @elseif ($jenis === 'date')
                                            <td>{{ $row->{$col['key']}?->format('d-m-Y') ?? '-' }}</td>
                                        @else
                                            <td>{{ \Illuminate\Support\Str::limit((string) (data_get($row, $col['key']) ?? '-'), 120) }}</td>
                                        @endif
                                    @endforeach
                                    <td class="text-end text-nowrap">
                                        <button type="button" class="btn btn-sm btn-aceh-outline me-1" data-aksi="edit"
                                                data-url="{{ route("admin.$kunci.update", $row->getKey()) }}"
                                                data-id="{{ $row->getKey() }}"
                                                data-record="{{ json_encode($rekam($row), JSON_UNESCAPED_UNICODE) }}"
                                                aria-label="Ubah"><i class="fa-solid fa-pen"></i></button>
                                        <form method="POST" action="{{ route("admin.$kunci.destroy", $row->getKey()) }}" class="d-inline js-hapus">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-aceh-danger" aria-label="Hapus"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($baris->isEmpty())
                    <p class="text-center text-muted py-4">{{ $q !== '' ? 'Tidak ada data yang cocok dengan "'.$q.'".' : 'Belum ada data.' }}</p>
                @endif
                @if ($baris->hasPages())
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3">
                        <span class="small text-muted">Menampilkan {{ $baris->firstItem() }}–{{ $baris->lastItem() }} dari {{ $baris->total() }}</span>
                        {{ $baris->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>

@include('admin._form')
@endsection

@push('js')
    <script src="{{ asset('assets/js/admin.js') }}" defer></script>
@endpush
