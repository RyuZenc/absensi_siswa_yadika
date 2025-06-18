<x-app-layout>
    @section('header', 'Dashboard Guru')

    <h2 class="text-xl font-semibold mb-4">Jadwal Mengajar Hari Ini</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($jadwals as $jadwal)
            <div class="bg-gray-50 p-4 rounded-lg shadow">
                <h3 class="font-bold text-lg">{{ $jadwal->mapel->nama_mapel }}</h3>
                <p class="text-gray-600">{{ $jadwal->kelas->nama_kelas }}</p>
                <p class="text-sm text-gray-500 mt-2">{{ date('H:i', strtotime($jadwal->jam_mulai)) }} -
                    {{ date('H:i', strtotime($jadwal->jam_selesai)) }}</p>
                <a href="{{ route('guru.absensi.show', $jadwal->id) }}"
                    class="mt-4 inline-block bg-gray-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded full text-center">
                    Mulai Absensi
                </a>
            </div>
        @empty
            <p>Tidak ada jadwal mengajar hari ini.</p>
        @endforelse
    </div>
</x-app-layout>
