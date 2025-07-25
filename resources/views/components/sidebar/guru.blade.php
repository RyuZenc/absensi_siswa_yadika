@php($sidebarOpen = $sidebarOpen ?? true)
<h3 class="font-semibold text-gray-400 uppercase tracking-wider mb-2 text-sm">Menu Guru</h3>

<x-sidebar.link href="{{ route('guru.dashboard') }}" icon="bi-grid-1x2-fill" title="Dashboard" :active="request()->routeIs('guru.dashboard')"
    :sidebarOpen="$sidebarOpen" />

<x-sidebar.link href="{{ route('guru.kelas.index') }}" icon="bi-collection-fill" title="Kelas yang Diajar"
    :active="request()->routeIs('guru.kelas.*')" :sidebarOpen="$sidebarOpen" />

<x-sidebar.link href="{{ route('guru.riwayat.riwayat') }}" icon="bi-clock-history" title="Riwayat Absensi"
    :active="request()->routeIs('guru.riwayat.riwayat') || request()->routeIs('guru.riwayat.detail')" :sidebarOpen="$sidebarOpen" />
