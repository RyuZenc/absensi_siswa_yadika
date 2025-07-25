<x-app-layout>
    @section('header', 'Jadwal Pelajaran')

    <h2 class="text-xl font-semibold mb-4">Jadwal Pelajaran Kelas {{ Auth::user()->siswa->kelas->nama }}</h2>

    @forelse ($jadwals as $hari => $list)
        <div class="mb-4 border rounded-lg">
            <div class="p-4 bg-gray-100 font-semibold text-gray-700">
                📅 {{ $hari }}
            </div>

            <div class="p-4 bg-white space-y-4">
                @foreach ($list as $jadwal)
                    <div class="border p-4 rounded-lg shadow-sm">
                        <div class="font-bold text-indigo-600">{{ $jadwal->mapel->nama_mapel }}</div>
                        <div class="text-sm text-gray-700">Guru: {{ $jadwal->guru->nama_lengkap }}</div>
                        <div class="text-sm text-gray-500 font-mono">
                            {{ date('H:i', strtotime($jadwal->jam_mulai)) }} -
                            {{ date('H:i', strtotime($jadwal->jam_selesai)) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <p class="text-center text-gray-500">Belum ada jadwal yang tersedia.</p>
    @endforelse
</x-app-layout>
