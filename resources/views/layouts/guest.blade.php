<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Absensi') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

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
        <a href="{{ url('/') }}">
            <img src="{{ asset('yadika/assets/images/sma-yadika-full.png') }}" alt="logo" class="logo">
        </a>
    </div>
    {{ $slot }}
</body>

</html>
