@php($sidebarOpen = $sidebarOpen ?? true)
<h3 class="font-semibold text-gray-400 uppercase tracking-wider mb-2 text-sm">Menu Guru</h3>

<x-sidebar.link href="{{ route('guru.dashboard') }}" icon="bi-grid-1x2-fill" title="Dashboard" :active="request()->routeIs('guru.dashboard')"
    :sidebarOpen="$sidebarOpen" />

<x-sidebar.link href="{{ route('guru.kelas.index') }}" icon="bi-collection-fill" title="Kelas yang Diajar"
    :active="request()->routeIs('guru.kelas.*')" :sidebarOpen="$sidebarOpen" />

<x-sidebar.link href="{{ route('guru.riwayat.riwayat') }}" icon="bi-clock-history" title="Riwayat Absensi"
    :active="request()->routeIs('guru.riwayat.riwayat') || request()->routeIs('guru.riwayat.detail')" :sidebarOpen="$sidebarOpen" />

<x-sidebar.link href="{{ route('guru.rekap.index') }}" icon="bi-clipboard2-check" title="Rekap Absensi"
    :active="request()->routeIs('guru.rekap.index')" :sidebarOpen="$sidebarOpen" />

@if (Auth::user()->guru && Auth::user()->guru->role === 'wali_kelas')
    <div class="space-y-2 flex-col space-y-2 flex-col gap-y-2mt-6 pt-4 border-t border-gray-200">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Menu Wali Kelas</h3>
        <x-sidebar.walikelas />
    </div>
@endif
