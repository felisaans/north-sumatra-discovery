{{-- Modal form SATU untuk semua modul (setara #modalForm di admin/index.html lama).
     Field dirender dari config/admin.php; admin.js mengisi nilai & mengatur action (POST tambah / PUT ubah).
     Bila validasi server gagal, halaman dimuat ulang dengan old() input dan modal dibuka lagi otomatis. --}}
@php
    $editId = old('_edit_id');
    $editId = is_string($editId) && ctype_digit($editId) ? $editId : '';
    $punyaGalat = $errors->any();
@endphp
<div class="modal fade" id="modalForm" tabindex="-1"
     data-store-url="{{ $moderasi ? '' : route("admin.$kunci.store") }}"
     data-label="{{ $modul['label'] }}"
     @if ($punyaGalat)
        data-buka-otomatis
        data-edit-id="{{ $editId }}"
        @if ($editId) data-edit-url="{{ route("admin.$kunci.update", $editId) }}" @endif
     @endif>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <form id="formResource" method="POST" action="{{ $moderasi ? '' : route("admin.$kunci.store") }}">
                @csrf
                <input type="hidden" name="_method" value="PUT" disabled id="inputMethod">
                <input type="hidden" name="_edit_id" value="{{ $editId }}" id="inputEditId">

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold admin-judul" id="judulModal">Tambah {{ $modul['label'] }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body row g-3">
                    @foreach ($modul['fields'] as $f)
                        @php
                            $nama = $f['name'];
                            $wajib = ($f['required'] ?? false) ? 'required' : '';
                            $lebar = $f['type'] === 'textarea' || $f['type'] === 'image-url' ? 'col-12' : 'col-md-6';
                            $nilai = old($nama);
                            $label = $f['label'].(! empty($f['hint']) ? ' ('.$f['hint'].')' : '');
                        @endphp
                        <div class="{{ $lebar }}">
                            <label class="form-label fw-semibold" for="f_{{ $nama }}">{{ $label }}</label>

                            @if ($f['type'] === 'textarea')
                                <textarea id="f_{{ $nama }}" class="form-control" name="{{ $nama }}" rows="3" {{ $wajib }}>{{ $nilai }}</textarea>

                            @elseif ($f['type'] === 'select')
                                <select id="f_{{ $nama }}" class="form-select" name="{{ $nama }}" {{ $wajib }}>
                                    <option value="">Pilih...</option>
                                    @foreach ($f['options'] as $o)
                                        <option value="{{ $o['value'] }}" @selected((string) $nilai === (string) $o['value'])>{{ $o['label'] }}</option>
                                    @endforeach
                                </select>

                            @elseif ($f['type'] === 'select-fk')
                                <select id="f_{{ $nama }}" class="form-select" name="{{ $nama }}" {{ $wajib }}>
                                    <option value="">Pilih...</option>
                                    @foreach ($opsiFk[$nama] ?? [] as $id => $teks)
                                        <option value="{{ $id }}" @selected((string) $nilai === (string) $id)>{{ $teks }}</option>
                                    @endforeach
                                </select>

                            @elseif ($f['type'] === 'image-url')
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <img src="{{ $nilai ?: 'https://placehold.co/600x600/F8E8D0/54205F?text=North+Sumatra+Discovery' }}" class="thumb" alt=""
                                         style="width:64px;height:64px;object-fit:cover;" data-preview-for="{{ $nama }}"
                                         onerror="this.style.visibility='hidden'">
                                    <input id="f_{{ $nama }}" type="url" class="form-control" name="{{ $nama }}" maxlength="500"
                                           placeholder="https://images.unsplash.com/..." value="{{ $nilai }}" data-preview-input>
                                </div>
                                <div class="form-text">Tempel URL gambar eksternal (mis. Unsplash). Kosongkan untuk memakai gambar bawaan.</div>

                            @elseif ($f['type'] === 'url')
                                <input id="f_{{ $nama }}" type="url" class="form-control" name="{{ $nama }}" maxlength="500"
                                       placeholder="https://maps.app.goo.gl/..." value="{{ $nilai }}" {{ $wajib }}>

                            @elseif ($f['type'] === 'number')
                                <input id="f_{{ $nama }}" type="number" min="0" class="form-control" name="{{ $nama }}" value="{{ $nilai }}" {{ $wajib }}>

                            @elseif ($f['type'] === 'date')
                                <input id="f_{{ $nama }}" type="date" class="form-control" name="{{ $nama }}" value="{{ $nilai }}" {{ $wajib }}>

                            @else
                                <input id="f_{{ $nama }}" type="text" class="form-control" name="{{ $nama }}" value="{{ $nilai }}"
                                       maxlength="{{ $f['max'] ?? 255 }}" {{ $wajib }}>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="modal-footer border-0 pt-0 flex-wrap">
                    <div class="small text-danger me-auto" id="pesanForm" role="alert">
                        @foreach ($errors->all() as $galat)<div>{{ $galat }}</div>@endforeach
                    </div>
                    <button type="button" class="btn btn-aceh-outline px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom px-4" id="btnSimpanForm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
