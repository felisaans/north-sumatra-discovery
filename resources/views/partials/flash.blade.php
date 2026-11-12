{{-- Pesan sukses/galat dari redirect (mis. "Ulasanmu sudah disimpan."). Hilang otomatis lewat flash.js --}}
@if (session('sukses') || session('galat'))
    <div class="flash-stack" aria-live="polite">
        @if (session('sukses'))
            <div class="alert alert-success alert-dismissible fade show" role="status" data-autohide="5000">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('sukses') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif
        @if (session('galat'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" data-autohide="8000">
                <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('galat') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif
    </div>
@endif
