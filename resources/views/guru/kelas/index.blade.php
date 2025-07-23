<x-app-layout>
    @section('header', 'Daftar Kelas & Mata Pelajaran yang Saya Ajar')
    <div class="w-full">

        <div class="overflow-x-auto bg-white rounded-lg shadow-md">

            <table id="sortableTable" class="w-full px-4 bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">
                            #
                            <button class="sort-btn ml-1" data-column="0">⬍</button>
                        </th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">
                            Nama Kelas
                            <button class="sort-btn ml-1" data-column="1">⬍</button>
                        </th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">
                            Mata Pelajaran
                            <button class="sort-btn ml-1" data-column="2">⬍</button>
                        </th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">
                            Jadwal Mengajar
                            <button class="sort-btn ml-1" data-column="3">⬍</button>
                        </th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($jadwals as $jadwal)
                        <tr class="border-b">
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ $jadwal->kelas->nama_kelas }}</td>
                            <td class="py-3 px-4">{{ $jadwal->mapel->nama_mapel }}</td>
                            <td class="py-3 px-4">
                                @php
                                    $hariMengajar = \App\Models\Jadwal::where('guru_id', Auth::user()->guru->id)
                                        ->where('kelas_id', $jadwal->kelas_id)
                                        ->where('mapel_id', $jadwal->mapel_id)
                                        ->orderBy('hari')
                                        ->get();
                                @endphp
                                <ul class="list-disc list-inside">
                                    @foreach ($hariMengajar as $item)
                                        <li>
                                            <strong>{{ $item->hari }}:</strong>
                                            {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
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
