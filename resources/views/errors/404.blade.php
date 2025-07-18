{{-- resources/views/errors/404.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="text-center">
        <h1 class="display-4 text-danger">404</h1>
        <p class="lead">Halaman yang Anda cari tidak ditemukan.</p>

        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mt-4">
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ url('/') }}" class="btn btn-primary">
                <i class="bi bi-house-door-fill"></i> Ke Beranda
            </a>
        </div>
    </div>

    {{-- Bootstrap Icons (opsional) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>
</body>

</html>
