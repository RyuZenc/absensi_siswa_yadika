<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased">
    <div class="flex h-screen bg-gray-100" x-data="{ isSidebarOpen: JSON.parse(localStorage.getItem('sidebarOpen') || 'true') }" x-init="$watch('isSidebarOpen', value => localStorage.setItem('sidebarOpen', JSON.stringify(value)))">

        <aside class="flex flex-col text-white p-4 transition-[width] duration-300 shrink-0"
            :class="isSidebarOpen ? 'w-64' : 'w-20'" style="background-color:rgb(56, 69, 70)">

            <div class="flex items-center shrink-0" :class="isSidebarOpen ? 'justify-between' : 'justify-center'">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3" x-show="isSidebarOpen">
                    <img src="{{ asset('yadika/assets/images/sma-yadika-full.png') }}" alt="Yadika Logo"
                        class="h-10 w-auto max-w-full">
                </a>


                </a>
                <button @click="isSidebarOpen = !isSidebarOpen"
                    class="p-2 rounded-md hover:bg-gray-700 focus:outline-none">
                    <i class="bi bi-record-circle text-xl text-indigo-300"></i>
                </button>
            </div>


            <nav class="mt-10 flex-1 flex-col gap-y-2 overflow-y-auto scrollbar-thin-dark">
                @if (Auth::user()->role == 'admin')
                    <h3 class="font-semibold text-gray-400 uppercase tracking-wider mb-2 transition-all"
                        :class="isSidebarOpen ? 'text-sm' : 'text-xs text-center'">Menu</h3>

                    <a href="{{ route('admin.dashboard') }}" title="Dashboard"
                        class="flex items-center gap-3 h-12 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900' : '' }}"
                        :class="!isSidebarOpen && 'justify-center'">
                        <i class="bi bi-grid-1x2-fill text-xl w-6 text-center"></i>
                        <span x-show="isSidebarOpen">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.kelas.index') }}" title="Manajemen Kelas"
                        class="flex items-center gap-3 h-12 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.kelas.*') ? 'bg-gray-900' : '' }}"
                        :class="!isSidebarOpen && 'justify-center'">
                        <i class="bi bi-house-door-fill text-xl w-6 text-center"></i>
                        <span x-show="isSidebarOpen">Manajemen Kelas</span>
                    </a>
                    <a href="{{ route('admin.mapel.index') }}" title="Manajemen Mapel"
                        class="flex items-center gap-3 h-12 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.mapel.*') ? 'bg-gray-900' : '' }}"
                        :class="!isSidebarOpen && 'justify-center'">
                        <i class="bi bi-book-fill text-xl w-6 text-center"></i>
                        <span x-show="isSidebarOpen">Manajemen Mapel</span>
                    </a>
                    <a href="{{ route('admin.guru.index') }}" title="Manajemen Guru"
                        class="flex items-center gap-3 h-12 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.guru.*') ? 'bg-gray-900' : '' }}"
                        :class="!isSidebarOpen && 'justify-center'">
                        <i class="bi bi-person-video3 text-xl w-6 text-center"></i>
                        <span x-show="isSidebarOpen">Manajemen Guru</span>
                    </a>
                    <a href="{{ route('admin.siswa.index') }}" title="Manajemen Siswa"
                        class="flex items-center gap-3 h-12 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.siswa.*') ? 'bg-gray-900' : '' }}"
                        :class="!isSidebarOpen && 'justify-center'">
                        <i class="bi bi-people-fill text-xl w-6 text-center"></i>
                        <span x-show="isSidebarOpen">Manajemen Siswa</span>
                    </a>
                    <a href="{{ route('admin.jadwal.index') }}" title="Manajemen Jadwal"
                        class="flex items-center gap-3 h-12 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.jadwal.*') ? 'bg-gray-900' : '' }}"
                        :class="!isSidebarOpen && 'justify-center'">
                        <i class="bi bi-calendar-week-fill text-xl w-6 text-center"></i>
                        <span x-show="isSidebarOpen">Manajemen Jadwal</span>
                    </a>
                @elseif(Auth::user()->role == 'guru')
                    <h3 class="font-semibold text-gray-400 uppercase tracking-wider mb-2 transition-all"
                        :class="isSidebarOpen ? 'text-sm' : 'text-xs text-center'">Menu Siswa</h3>
                    <a href="{{ route('guru.dashboard') }}" title="Dashboard"
                        class="flex items-center gap-3 h-12 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('guru.dashboard') ? 'bg-gray-900' : '' }}"
                        :class="!isSidebarOpen && 'justify-center'">
                        <i class="bi bi-grid-1x2-fill text-xl w-6 text-center"></i>
                        <span x-show="isSidebarOpen">Dashboard</span>
                    </a>
                @elseif(Auth::user()->role == 'siswa')
                    <h3 class="font-semibold text-gray-400 uppercase tracking-wider mb-2 transition-all"
                        :class="isSidebarOpen ? 'text-sm' : 'text-xs text-center'">Menu Siswa</h3>
                    <a href="{{ route('siswa.dashboard') }}" title="Dashboard"
                        class="flex items-center gap-3 h-12 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('siswa.dashboard') ? 'bg-gray-900' : '' }}"
                        :class="!isSidebarOpen && 'justify-center'">
                        <i class="bi bi-grid-1x2-fill text-xl w-6 text-center"></i>
                        <span x-show="isSidebarOpen">Dashboard</span>
                    </a>
                @endif
            </nav>

            <div class="mt-auto pt-4 border-t border-gray-700 flex flex-col gap-y-2 shrink-0">
                <a href="{{ route('profile.edit') }}" title="Profile"
                    class="flex items-center gap-3 h-12 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('profile.edit') ? 'bg-gray-900' : '' }}"
                    :class="!isSidebarOpen && 'justify-center'">
                    <i class="bi bi-person-circle text-xl w-6 text-center"></i>
                    <span x-show="isSidebarOpen">Profile</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 h-12 px-4 rounded transition duration-200 hover:bg-red-700 bg-red-600"
                        :class="!isSidebarOpen && 'justify-center'">
                        <i class="bi bi-box-arrow-left text-xl w-6 text-center"></i>
                        <span x-show="isSidebarOpen">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 p-6 md:p-10 overflow-y-auto scrollbar-thin-dark">
            <header class="mb-8 flex justify-between items-center flex-wrap">
                <h1 class="text-3xl font-bold text-gray-800 mb-2 md:mb-0">@yield('header')</h1>
                <div id="realtime-clock"
                    class="text-lg font-semibold text-gray-600 bg-white px-4 py-2 rounded-lg shadow-sm"></div>
            </header>

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

            <footer class="text-center text-gray-500 mt-8 py-4 shrink-0">
                Made & Design with <span class="text-red-500">❤</span>
            </footer>
        </main>
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
        updateClock();
    </script>
    @stack('scripts')
</body>

</html>
