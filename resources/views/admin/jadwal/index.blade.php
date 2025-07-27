<x-app-layout>
    @section('header', 'Daftar Jadwal Pelajaran')

    <div class="mb-4 text-right">
        <a href="{{ route('admin.jadwal.create') }}"
            class="text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md text-sm transition w-full sm:w-auto">
            Tambah Jadwal
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-3 bg-white border-b border-gray-200">
            @forelse ($jadwals as $kelas_id => $jadwalPerKelas)
                <h3 class="text-lg font-semibold mb-3">{{ $jadwalPerKelas->first()->kelas->tingkat }} -
                    {{ $jadwalPerKelas->first()->kelas->nama_kelas }}</h3>
                <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                    <table id="sortableTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th scope="col" class="text-left py-3 px-4 uppercase font-semibold text-sm">
                                    Hari<button class="sort-btn ml-1" data-column="0">⬍</button>
                                </th>
                                <th scope="col" class="text-left py-3 px-4 uppercase font-semibold text-sm">
                                    Jam Mulai<button class="sort-btn ml-1" data-column="1">⬍</button>
                                </th>
                                <th scope="col" class="text-left py-3 px-4 uppercase font-semibold text-sm">
                                    Jam Selesai<button class="sort-btn ml-1" data-column="2">⬍</button>
                                </th>
                                <th scope="col" class="text-left py-3 px-4 uppercase font-semibold text-sm">
                                    Mata Pelajaran<button class="sort-btn ml-1" data-column="3">⬍</button>
                                </th>
                                <th scope="col" class="text-left py-3 px-4 uppercase font-semibold text-sm">
                                    Guru Jadwal<button class="sort-btn ml-1" data-column="4">⬍</button>
                                </th>
                                <th scope="col" class="text-left py-3 px-4 uppercase font-semibold text-sm">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach ($jadwalPerKelas as $jadwal)
                                <tr class="border-b">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $jadwal->hari }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $jadwal->mapel->nama_mapel }}
                                        @if ($jadwal->mapel->guru)
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $jadwal->guru->nama_lengkap }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}"
                                                class="bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-1 px-3 rounded-md text-sm transition">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data jadwal ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="search" value="{{ $search ?? '' }}">
                                                <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-1 px-3 rounded-md text-sm transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <p class="text-center text-gray-500">Tidak ada jadwal yang tersedia.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
