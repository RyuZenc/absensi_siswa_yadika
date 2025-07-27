<x-sidebar.link :href="route('walikelas.cek_kelas')" :active="request()->routeIs('walikelas.cek_kelas')" title="Cek Kelas" icon="bi-journal-bookmark-fill">
    Cek Kelas
</x-sidebar.link>
<x-sidebar.link :href="route('walikelas.rekap.index')" :active="request()->routeIs('walikelas.rekap.index')" title="Cek Absen" icon="bi-journal-check">
    Cek Absen
</x-sidebar.link>
