<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <div class="flex flex-col md:flex-row">
            <!-- Sidebar -->
            <aside class="w-full md:w-64 bg-gray-800 text-white p-4">
                <a href="{{ route('dashboard') }}">
                    <h2 class="text-2xl font-bold mb-10 text-center md:text-left">Absensi Siswa</h2>
                </a>
                <nav>
                    @if (Auth::user()->role == 'admin')
                        <h3 class="font-semibold text-gray-400 uppercase tracking-wider mb-2">Admin Menu</h3>
                        <a href="{{ route('admin.dashboard') }}"
                            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900' : '' }}">Dashboard</a>
                        <a href="{{ route('admin.kelas.index') }}"
                            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.kelas.*') ? 'bg-gray-900' : '' }}">Manajemen
                            Kelas</a>
                        <a href="{{ route('admin.mapel.index') }}"
                            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.mapel.*') ? 'bg-gray-900' : '' }}">Manajemen
                            Mapel</a>
                        <a href="{{ route('admin.guru.index') }}"
                            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.guru.*') ? 'bg-gray-900' : '' }}">Manajemen
                            Guru</a>
                        <a href="{{ route('admin.siswa.index') }}"
                            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.siswa.*') ? 'bg-gray-900' : '' }}">Manajemen
                            Siswa</a>
                        <a href="{{ route('admin.jadwal.index') }}"
                            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.jadwal.*') ? 'bg-gray-900' : '' }}">Manajemen
                            Jadwal</a>
                    @elseif(Auth::user()->role == 'guru')
                        <h3 class="font-semibold text-gray-400 uppercase tracking-wider mb-2">Guru Menu</h3>
                        <a href="{{ route('guru.dashboard') }}"
                            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('guru.dashboard') ? 'bg-gray-900' : '' }}">Dashboard</a>
                    @elseif(Auth::user()->role == 'siswa')
                        <h3 class="font-semibold text-gray-400 uppercase tracking-wider mb-2">Siswa Menu</h3>
                        <a href="{{ route('siswa.dashboard') }}"
                            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('siswa.dashboard') ? 'bg-gray-900' : '' }}">Dashboard</a>
                    @endif

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="mt-10">
                        @csrf
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="block text-center py-2.5 px-4 rounded transition duration-200 hover:bg-red-700 bg-red-600">
                            Logout
                        </a>
                    </form>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6 md:p-10">
                <header class="mb-8 flex justify-between items-center flex-wrap">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2 md:mb-0">@yield('header')</h1>
                    <div id="realtime-clock"
                        class="text-lg font-semibold text-gray-600 bg-white px-4 py-2 rounded-lg shadow-sm"></div>
                </header>

                <!-- Menampilkan notifikasi sukses atau error -->
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="bg-white p-6 md:p-8 rounded-lg shadow-md">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
    <script>
        function updateClock() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                'Oktober', 'November', 'Desember'
            ];

            const day = days[now.getDay()];
            const date = now.getDate();
            const month = months[now.getMonth()];
            const year = now.getFullYear();

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            const clockString = `${day}, ${date} ${month} ${year} | ${hours}:${minutes}:${seconds}`;

            const clockElement = document.getElementById('realtime-clock');
            if (clockElement) {
                clockElement.textContent = clockString;
            }
        }

        setInterval(updateClock, 1000);
        updateClock(); // Panggil sekali saat halaman dimuat
    </script>
</body>

</html>
