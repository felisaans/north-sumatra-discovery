{{-- Modal "Ceritakan perjalananmu" (F06). WAJIB LOGIN: tamu hanya melihat ajakan masuk/daftar.
     Tanpa JS tetap jalan: form multipart biasa ke POST /pengalaman. Dengan JS: pengalaman.js (progres, tanpa reload). --}}
@props(['daftarDaerah'])
@php $user = auth()->user(); @endphp
<div class="modal fade" id="modalUnggahPengalaman" tabindex="-1" aria-hidden="true" @if ($errors->any() && old('_form') === 'pengalaman') data-buka-otomatis @endif>
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0  p-3">
            <div class="modal-header border-0">
                <div>
                    <h5 class="modal-title font-editorial fw-bold text-teal mb-1">Ceritakan perjalananmu</h5>
                    <p class="small text-muted m-0">Ceritamu akan muncul di beranda, di antara cerita pengunjung lain.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                @guest
                    {{-- Belum login: unggah pengalaman hanya untuk pengguna terdaftar --}}
                    <div class="text-center py-4 px-2">
                        <i class="fa-solid fa-lock fs-1 text-teal mb-3 d-block" aria-hidden="true"></i>
                        <h6 class="fw-bold mb-2">Masuk dulu untuk berbagi cerita</h6>
                        <p class="small text-muted mb-4">Agar cerita tetap terpercaya, hanya pengguna yang sudah masuk yang bisa mengunggah pengalaman.</p>
                        <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                            <a href="{{ route('masuk') }}" class="btn btn-aceh-primary px-4"><i class="fa-solid fa-right-to-bracket me-2"></i>Masuk atau Daftar</a>
                            <button type="button" class="btn btn-aceh-outline px-4" data-bs-dismiss="modal">Nanti saja</button>
                        </div>
                    </div>
                @else

                    <div class="avatar-info-card mb-4">
                        <div><x-avatar :nama="$user->name" :email="$user->email" :ukuran="56" kelas="avatar-info-avatar" /></div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-0">{{ $user->name }}</h6>
                            <p class="small text-muted mb-0">Nama akan otomatis dipakai sebagai nama pengunggah.</p>
                        </div>
                    </div>

                <div id="pengalamanGalat" role="alert" @class(['alert alert-danger small', 'd-none' => ! ($errors->any() && old('_form') === 'pengalaman')])>
                    @if (old('_form') === 'pengalaman')
                        @foreach ($errors->all() as $galat)<div>{{ $galat }}</div>@endforeach
                    @endif
                </div>

                <form id="formPengalaman" method="POST" action="{{ route('pengalaman.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_form" value="pengalaman">

                    {{-- Nama pengunggah diambil otomatis dari akun yang sedang login --}}

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold" for="inputDaerahPengalaman">Daerah <span class="text-danger">*</span></label>
                            <select id="inputDaerahPengalaman" name="daerah_id" class="form-select" required>
                                <option value="" disabled @selected(! old('daerah_id'))>Pilih daerah...</option>
                                @foreach ($daftarDaerah as $d)
                                    <option value="{{ $d->id }}" @selected((string) old('daerah_id') === (string) $d->id)>{{ $d->nama_daerah }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold" for="inputDestinasiPengalaman">Destinasi yang Dikunjungi <span class="text-danger">*</span></label>
                            <input type="text" id="inputDestinasiPengalaman" name="destinasi" class="form-control"
                                   placeholder="Misal: Kesawan, Medan atau Tuktuk, Samosir" maxlength="100" value="{{ old('destinasi') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold" for="inputRatingPengalaman">Rating <span class="text-danger">*</span></label>
                            <select id="inputRatingPengalaman" name="rating" class="form-select" required>
                                @foreach ([5, 4, 3, 2, 1] as $n)
                                    <option value="{{ $n }}" @selected((int) old('rating', 5) === $n)>{{ str_repeat('★', $n) }}{{ str_repeat('☆', 5 - $n) }} ({{ $n }}.0)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Foto Destinasi <span class="text-danger">*</span></label>
                        <input type="file" id="inputFotoPengalaman" name="foto" accept="image/png, image/jpeg, image/webp" class="d-none" required>

                        <div id="uploadDropzone" class="upload-dropzone" role="button" tabindex="0">
                            <i class="fa-solid fa-cloud-arrow-up d-block"></i>
                            <div class="fw-semibold">Klik untuk pilih foto, atau seret &amp; lepas di sini</div>
                            <small class="text-muted">Format: JPG, PNG, WEBP — Maks 5MB</small>
                        </div>

                        <div id="filePreview" class="mt-3 d-none">
                            <div id="filePreviewBg" class="overflow-hidden position-relative"
                                 style="height: 180px; background-size: cover; background-position: center;">
                                <button type="button" id="removeFileBtn" class="btn btn-sm btn-light position-absolute"
                                        style="top: 10px; right: 10px; border-radius: 50%; width: 34px; height: 34px; padding: 0;" aria-label="Hapus foto">
                                    <i class="fa-solid fa-xmark text-danger"></i>
                                </button>
                            </div>
                            <p class="small text-muted mt-2 mb-0">
                                <i class="fa-solid fa-file-image me-1"></i>
                                <span id="fileName">foto.jpg</span> — <span id="fileSize">0 KB</span>
                            </p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold" for="inputTeksPengalaman">Cerita Pengalaman <span class="text-danger">*</span></label>
                        <textarea id="inputTeksPengalaman" name="cerita" class="form-control" rows="4" maxlength="2000"
                                  placeholder="Ceritakan momen paling berkesan, dari rasa sampai suasananya..." required>{{ old('cerita') }}</textarea>
                    </div>

                    <div id="uploadProgress" class="mb-3 d-none">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="fa-solid fa-spinner fa-spin text-teal"></i>
                            <small class="fw-semibold" id="uploadStatusText">Mengunggah...</small>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div id="uploadProgressBar" class="progress-bar" style="width: 0%; background-color: var(--color-teal);"></div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-aceh-outline flex-fill py-2" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="submitBtn" class="btn btn-aceh-primary flex-fill py-2">
                            <i class="fa-solid fa-paper-plane me-2"></i>Kirim Cerita
                        </button>
                    </div>
                </form>
                @endguest
            </div>
        </div>
    </div>
</div>
