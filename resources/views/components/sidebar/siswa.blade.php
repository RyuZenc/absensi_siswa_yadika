@php($sidebarOpen = $sidebarOpen ?? true)
<h3 class="font-semibold text-gray-400 uppercase tracking-wider mb-2 text-sm">Menu Siswa</h3>

<x-sidebar.link href="{{ route('siswa.dashboard') }}" icon="bi-grid-1x2-fill" title="Dashboard" :active="request()->routeIs('siswa.dashboard')"
    :sidebarOpen="$sidebarOpen" />

<x-sidebar.link href="{{ route('siswa.jadwal.index') }}" icon="bi-calendar-week" title="Jadwal Pelajaran" :active="request()->routeIs('siswa.jadwal.index')"
    :sidebarOpen="$sidebarOpen" />
