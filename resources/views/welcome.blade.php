<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login - Sistem Absensi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #CBDDD6FF;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 20px;
        }

        .logo {
            width: 300px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #1d3557;
        }

        .subtitle {
            color: #4caf50;
            font-weight: bold;
            font-size: 20px;
            letter-spacing: 1px;
        }

        .login-card {
            background-color: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 480px;
            margin-bottom: 20px;
        }

        .btn-role {
            font-size: 16px;
            padding: 12px;
            margin-bottom: 15px;
        }

        .btn-orange {
            background-color: #f9a825;
            color: white;
        }

        .btn-orange:hover {
            background-color: #f57f17;
        }

        .info-box {
            background-color: #ffffff;
            border-left: 5px solid #0d6efd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 480px;
            font-size: 14px;
            color: #333;
        }

        .info-box h6 {
            font-weight: bold;
            margin-bottom: 10px;
            color: #0d6efd;
        }
    </style>
</head>

<body>

    <div class="text-center mb-4">
        <img src="{{ asset('matrix-admin/assets/images/sma-yadika-5.png') }}" alt="logo" class="logo">
    </div>

    <div class="login-card text-center">
        <div class="title mb-4">Selamat Datang di Sistem Absensi</div>
        <p class="mb-2 text-secondary">Silakan pilih peran Anda untuk melanjutkan:</p>

        <a href="{{ route('login.siswa') }}" class="btn btn-orange btn-role w-100">
            <i class="bi bi-person-fill me-2"></i> Login Siswa
        </a>
        <a href="{{ route('login.guru') }}" class="btn btn-success btn-role w-100">
            <i class="bi bi-person-badge-fill me-2"></i> Login Guru
        </a>
        <a href="{{ route('login.admin') }}" class="btn btn-outline-primary btn-role w-100">
            <i class="bi bi-person-workspace me-2"></i> Data Dashboard
        </a>
    </div>

    <!-- Kotak Informasi -->
    <div class="info-box">
        <h6><i class="bi bi-info-circle-fill me-1"></i> Informasi</h6>
        <ul class="mb-0 ps-3">
            <li>Pastikan Anda menggunakan akun yang sesuai dengan peran Anda.</li>
            <li>Hubungi admin jika mengalami masalah saat login.</li>
            <li>Absensi hanya bisa dilakukan pada jam yang telah ditentukan.</li>
        </ul>
    </div>

</body>

</html>
