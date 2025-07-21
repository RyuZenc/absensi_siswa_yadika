<x-app-layout>
    @section('header', 'Manajemen Jadwal')

    <div class="w-full">
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.jadwal.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition">
                + Tambah Jadwal
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table id="sortableTable" class="w-full px-4 bg-white text-sm">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">
                            #
                            <button class="sort-btn ml-1" data-column="0">⬍</button>
                        </th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Hari
                            <button class="sort-btn ml-1" data-column="1">⬍</button>
                        </th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Jam
                            <button class="sort-btn ml-1" data-column="2">⬍</button>
                        </th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Kelas
                            <button class="sort-btn ml-1" data-column="3">⬍</button>
                        </th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Mata Pelajaran
                            <button class="sort-btn ml-1" data-column="4">⬍</button>
                        </th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Guru
                            <button class="sort-btn ml-1" data-column="5">⬍</button>
                        </th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($jadwals as $jadwal)
                        <tr class="border-b">
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ $jadwal->hari }}</td>
                            <td class="py-3 px-4">
                                {{ date('H:i', strtotime($jadwal->jam_mulai)) }} -
                                {{ date('H:i', strtotime($jadwal->jam_selesai)) }}
                            </td>
                            <td class="py-3 px-4">{{ $jadwal->kelas->nama_kelas }}</td>
                            <td class="py-3 px-4">{{ $jadwal->mapel->nama_mapel }}</td>
                            <td class="py-3 px-4">{{ $jadwal->guru->nama_lengkap }}</td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}"
                                        class="bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-1 px-3 rounded-md text-sm transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-1 px-3 rounded-md text-sm transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Tidak ada data jadwal.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
