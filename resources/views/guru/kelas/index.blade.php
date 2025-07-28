<x-app-layout>
    @section('header', 'Daftar Kelas & Mata Pelajaran yang Saya Ajar')
    <div class="w-full">
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="w-full px-4 bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">#</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama Kelas</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Mata Pelajaran</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Jadwal Mengajar</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($jadwals as $kelasId => $groupedJadwal)
                        <tr class="bg-gray-100">
                            <td colspan="4" class="px-4 py-2 font-bold">
                                {{ $groupedJadwal->first()->kelas->tingkat . ' - ' . $groupedJadwal->first()->kelas->nama_kelas }}
                            </td>
                        </tr>

                        @foreach ($groupedJadwal->sortBy(fn($j) => array_search($j->hari, ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'])) as $jadwal)
                            <tr class="border-b">
                                <td class="py-3 px-4">{{ $loop->iteration }}</td>
                                <td class="py-3 px-4">{{ $jadwal->kelas->tingkat . ' - ' . $jadwal->kelas->nama_kelas }}
                                </td>
                                <td class="py-3 px-4">{{ $jadwal->mapel->nama_mapel }}</td>
                                <td class="py-3 px-4">
                                    <ul class="list-disc list-inside">
                                        <li>
                                            <strong>{{ $jadwal->hari }}:</strong>
                                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Anda belum memiliki jadwal mengajar.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
