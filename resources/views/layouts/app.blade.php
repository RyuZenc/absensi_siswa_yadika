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
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body class="font-sans antialiased">
    <div class="flex h-screen bg-gray-100" x-data="{ isSidebarOpen: JSON.parse(localStorage.getItem('sidebarOpen') || 'true') }" x-init="$watch('isSidebarOpen', value => localStorage.setItem('sidebarOpen', JSON.stringify(value)))">
        <aside
            class="fixed inset-y-0 left-0 z-50 flex flex-col text-white p-4 transition-[width] duration-300 shrink-0 bg-gray-800 md:relative md:flex"
            :class="{
                'w-64': isSidebarOpen,
                'w-20': !isSidebarOpen,
                'hidden': !isSidebarOpen,
                'flex': isSidebarOpen,
                'md:flex': true
            }"
            style="background-color:rgb(56, 69, 70)">

            <div class="flex items-center shrink-0" :class="isSidebarOpen ? 'justify-between' : 'justify-center'">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3" x-show="isSidebarOpen">
                    <img src="{{ asset('yadika/assets/images/sma-yadika-full.png') }}" alt="Yadika Logo"
                        style="width: 300px; height: auto;">

                </a>
                <button @click="isSidebarOpen = !isSidebarOpen"
                    class="p-2 rounded-md hover:bg-gray-700 focus:outline-none md:hidden">
                    <i :class="isSidebarOpen ? 'bi bi-x-lg' : 'bi bi-list'" class="text-xl text-indigo-300"></i>
                </button>
                <button @click="isSidebarOpen = !isSidebarOpen"
                    class="p-2 rounded-md hover:bg-gray-700 focus:outline-none hidden md:inline-flex">
                    <i class="bi bi-record-circle text-xl text-indigo-300"></i>
                </button>
            </div>

            <nav class="mt-10 flex-0 flex-col gap-y-2 overflow-y-auto overflow-x-hidden scrollbar-thin-dark">
                @includeWhen(Auth::user()->role === 'admin', 'components.sidebar.admin', [
                    'sidebarOpen' => true,
                ])
                @includeWhen(Auth::user()->role === 'guru', 'components.sidebar.guru', ['sidebarOpen' => true])
                @includeWhen(Auth::user()->role === 'siswa', 'components.sidebar.siswa', [
                    'sidebarOpen' => true,
                ])
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
                    <button type="submit" title="Logout"
                        class="w-full flex items-center gap-3 h-12 px-4 rounded transition duration-200 hover:bg-red-700 bg-red-600"
                        :class="!isSidebarOpen && 'justify-center'">
                        <i class="bi bi-box-arrow-left text-xl w-6 text-center"></i>
                        <span x-show="isSidebarOpen">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 p-6 md:p-6 overflow-y-auto">
            <div
                class="sticky backdrop-blur-md bg-white/90 top-0 z-40 bg-white rounded-b-lg shadow-md p-4 md:p-6 mb-6 flex items-center justify-between flex-wrap md:flex-nowrap gap-4">
                <div class="flex items-center gap-4">
                    <button @click="isSidebarOpen = !isSidebarOpen"
                        class="p-2 rounded-md hover:bg-gray-100 focus:outline-none md:hidden">
                        <i :class="isSidebarOpen ? 'bi bi-x-lg' : 'bi bi-list'" class="text-xl text-gray-600"></i>
                    </button>
                    <h2 class="text-lg font-bold text-gray-700 uppercase">
                        Absensi SMA Yadika
                    </h2>
                </div>

                <div class="flex items-center gap-6 ml-auto">
                    <div id="realtime-clock" class="text-sm text-gray-600 font-medium"></div>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random"
                                class="w-8 h-8 rounded-full" alt="Avatar">
                            <span class="text-sm font-medium text-gray-700 hidden md:block">
                                {{ Auth::user()->name }}
                            </span>
                            <i class="bi bi-chevron-down text-sm text-gray-500"></i>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                            <div class="px-4 py-2 text-sm text-gray-700 border-b font-semibold">
                                {{ Auth::user()->name }}
                            </div>
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Edit Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 md:p-8 rounded-lg shadow-md">
                {{ $slot }}
            </div>

            <footer class="text-center text-gray-500 mt-8 py-4 shrink-0">
                Made & Design with <span class="text-red-500">❤</span>
            </footer>
        </main>

    </div>
    @stack('scripts')
</body>

</html>
