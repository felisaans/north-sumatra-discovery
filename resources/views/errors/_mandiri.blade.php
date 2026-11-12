{{-- Halaman error tanpa ketergantungan database/sesi. Dipakai errors/500 dan errors/503. --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judul }} — North Sumatra Discovery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body class="d-flex align-items-center justify-content-center text-center" style="min-height:100vh">
    <main class="container" style="max-width:520px">
        <i class="fa-solid {{ $ikon }} fs-1 text-muted mb-3 d-block"></i>
        <h1 class="h3 fw-bold">{{ $judul }}</h1>
        <p class="text-muted">{{ $teks }}</p>
        <a href="/" class="btn btn-primary-custom px-4">Kembali ke Beranda</a>
    </main>
</body>
</html>
