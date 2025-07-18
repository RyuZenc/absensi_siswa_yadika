<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sistem Absensi Siswa</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles (Using Tailwind CSS CDN for simplicity in this file) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="relative min-h-screen flex flex-col items-center justify-center">

        <!-- Navigation Bar -->
        <div class="absolute top-0 right-0 p-6 text-right">
            @auth
                <!-- Jika pengguna sudah login -->
                <a href="{{ url('/dashboard') }}"
                    class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 mr-4">Dashboard</a>

                <!-- Form untuk Tombol Logout -->
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                        Logout
                    </button>
                </form>
            @endauth
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto p-6 lg:p-8 text-center">
            <div class="flex justify-center">
                <!-- Anda bisa mengganti ini dengan logo sekolah -->
                <svg class="h-16 w-auto text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <div class="mt-8">
                <h1 class="text-4xl font-bold text-gray-900">Selamat Datang di Sistem Absensi</h1>
                <p class="mt-4 text-lg text-gray-600">
                    Aplikasi untuk mengelola kehadiran siswa secara modern, cepat, dan efisien.
                </p>
            </div>

            <div class="mt-10">
                <a href="{{ Auth::check() ? url('/dashboard') : route('login') }}"
                    class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300">
                    Mulai
                </a>
            </div>
        </div>
    </div>
</body>

</html>
