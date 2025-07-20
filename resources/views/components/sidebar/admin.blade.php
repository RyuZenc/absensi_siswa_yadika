@php($sidebarOpen = $sidebarOpen ?? true)
<h3 class="font-semibold text-gray-400 uppercase tracking-wider mb-2 text-sm">Menu</h3>

<x-sidebar.link href="{{ route('admin.dashboard') }}" icon="bi-grid-1x2-fill" title="Dashboard" :active="request()->routeIs('admin.dashboard')"
    :sidebarOpen="$sidebarOpen" />

<x-sidebar.link href="{{ route('admin.kelas.index') }}" icon="bi-house-door-fill" title="Manajemen Kelas" :active="request()->routeIs('admin.kelas.*')"
    :sidebarOpen="$sidebarOpen" />

<x-sidebar.link href="{{ route('admin.mapel.index') }}" icon="bi-book-fill" title="Manajemen Mapel" :active="request()->routeIs('admin.mapel.*')"
    :sidebarOpen="$sidebarOpen" />

<x-sidebar.link href="{{ route('admin.guru.index') }}" icon="bi-person-video3" title="Manajemen Guru" :active="request()->routeIs('admin.guru.*')"
    :sidebarOpen="$sidebarOpen" />

<x-sidebar.link href="{{ route('admin.siswa.index') }}" icon="bi-people-fill" title="Manajemen Siswa" :active="request()->routeIs('admin.siswa.*')"
    :sidebarOpen="$sidebarOpen" />

<x-sidebar.link href="{{ route('admin.jadwal.index') }}" icon="bi-calendar-week-fill" title="Manajemen Jadwal"
    :active="request()->routeIs('admin.jadwal.*')" :sidebarOpen="$sidebarOpen" />
