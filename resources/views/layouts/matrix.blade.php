<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <title>{{ $title ?? config('app.name', 'Sistem Absensi Siswa') }}</title>

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('matrix/assets/images/favicon.png') }}" />

    <!-- Fonts (optional) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Matrix Admin CSS -->
    <link href="{{ asset('matrix-admin/assets/libs/flot/css/float-chart.css') }}" rel="stylesheet" />
    <link href="{{ asset('matrix-admin/dist/css/style.min.css') }}" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
        data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

        <!-- HEADER -->
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin5">
                    <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                        <b class="logo-icon p-md-0">
                            <img src="{{ asset('matrix-admin/assets/images/sma-yadika-logo.png') }}" alt="logo"
                                class="light-logo" style="height: 50px;" />
                        </b>
                        <span class="logo-text ms-2">
                            <img src="{{ asset('matrix-admin/assets/images/sma-yadika-text.png') }}" alt="text-logo"
                                class="light-logo" style="height: 50px;" />
                        </span>
                    </a>
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">
                        <i class="ti-menu ti-close"></i>
                    </a>
                </div>

                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <ul class="navbar-nav float-start me-auto">
                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)"
                                data-sidebartype="mini-sidebar">
                                <i class="mdi mdi-menu font-24"></i>
                            </a>
                        </li>
                    </ul>

                    <ul class="navbar-nav float-end">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic d-flex align-items-center"
                                href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img src="{{ asset('matrix-admin/assets/images/users/1.jpg') }}" alt="user"
                                    class="rounded-circle" width="31" height="31" />
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end user-dd animated"
                                aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="mdi mdi-account me-1 ms-1"></i> My Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fa fa-power-off me-1 ms-1"></i> Logout
                                    </button>
                                </form>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- SIDEBAR -->
        <aside class="left-sidebar" data-sidebarbg="skin5">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav" class="pt-4">
                        @if (Auth::user()->role == 'admin')
                            <li class="sidebar-item"><a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i
                                        class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span></a>
                            </li>
                            <li class="sidebar-item"><a href="{{ route('admin.kelas.index') }}" class="sidebar-link"><i
                                        class="mdi mdi-school"></i><span class="hide-menu">Manajemen Kelas</span></a>
                            </li>
                            <li class="sidebar-item"><a href="{{ route('admin.mapel.index') }}"
                                    class="sidebar-link"><i class="mdi mdi-book-open-variant"></i><span
                                        class="hide-menu">Manajemen
                                        Mapel</span></a></li>
                            <li class="sidebar-item"><a href="{{ route('admin.guru.index') }}"
                                    class="sidebar-link"><i class="mdi mdi-account-star"></i><span
                                        class="hide-menu">Manajemen
                                        Guru</span></a></li>
                            <li class="sidebar-item"><a href="{{ route('admin.siswa.index') }}"
                                    class="sidebar-link"><i class="mdi mdi-account-multiple"></i><span
                                        class="hide-menu">Manajemen Siswa</span></a></li>
                            <li class="sidebar-item"><a href="{{ route('admin.jadwal.index') }}"
                                    class="sidebar-link"><i class="mdi mdi-calendar-clock"></i><span
                                        class="hide-menu">Manajemen Jadwal</span></a></li>
                        @elseif(Auth::user()->role == 'guru')
                            <li class="sidebar-item"><a href="{{ route('guru.dashboard') }}" class="sidebar-link"><i
                                        class="mdi mdi-view-dashboard"></i><span
                                        class="hide-menu">Dashboard</span></a></li>
                        @elseif(Auth::user()->role == 'siswa')
                            <li class="sidebar-item"><a href="{{ route('siswa.dashboard') }}"
                                    class="sidebar-link"><i class="mdi mdi-view-dashboard"></i><span
                                        class="hide-menu">Dashboard</span></a></li>
                        @endif
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- CONTENT -->
        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <h4 class="page-title">@yield('header')</h4>
                        <div id="realtime-clock" class="text-muted small"></div>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card rounded-lg shadow-md">
                    <div class="card-body">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <footer class="footer text-center">
                <!-- All Rights Reserved by Matrix-admin. Designed and Developed by
                <a href="https://www.wrappixel.com">WrapPixel</a>. -->
            </footer>
        </div>
    </div>

    <!-- JS scripts -->
    <script src="{{ asset('matrix-admin/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('matrix-admin/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('matrix-admin/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('matrix-admin/assets/extra-libs/sparkline/sparkline.js') }}"></script>
    <script src="{{ asset('matrix-admin/dist/js/waves.js') }}"></script>
    <script src="{{ asset('matrix-admin/dist/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('matrix-admin/dist/js/custom.min.js') }}"></script>

    <!-- Clock JS -->
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
            if (clockElement) clockElement.textContent = clockString;
        }

        setInterval(updateClock, 1000);
        updateClock();
    </script>

    @stack('scripts')
</body>

</html>
