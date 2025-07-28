<x-app-layout>
    @section('header', 'Daftar Mata Pelajaran')

    <div class="mb-4 text-right">
        <a href="{{ route('admin.mapel.create') }}"
            class="text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md text-sm transition w-full sm:w-auto">
            Tambah Mata Pelajaran
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table id="sortableTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th scope="col" class="text-left py-3 px-4 uppercase font-semibold text-sm">
                            #<button class="sort-btn ml-1" data-column="0">⬍</button>
                        </th>
                        <th scope="col" class="text-left py-3 px-4 uppercase font-semibold text-sm">
                            Nama Mata Pelajaran<button class="sort-btn ml-1" data-column="1">⬍</button>
                        </th>
                        <th scope="col" class="text-left py-3 px-4 uppercase font-semibold text-sm">
                            Guru Pengajar<button class="sort-btn ml-1" data-column="2">⬍</button>
                        </th>
                        <th scope="col" class="text-left py-3 px-4 uppercase font-semibold text-sm">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($mapels as $mapel)
                        <tr class="border-b">
                            <td class="py-3 px-4 whitespace-nowrap">
                                {{ $loop->iteration }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                {{ $mapel->nama_mapel }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                {{ $mapel->guru->nama_lengkap ?? 'Belum Ditugaskan' }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.mapel.edit', $mapel->id) }}"
                                        class="bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-1 px-3 rounded-md text-sm transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.mapel.destroy', $mapel->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data mata pelajaran ini?');">
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
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                Tidak ada data mata pelajaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
