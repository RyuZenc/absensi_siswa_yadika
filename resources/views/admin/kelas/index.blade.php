<x-app-layout>
    @section('header', 'Manajemen Kelas')

    <div class="w-full">
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.kelas.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition">
                + Tambah Kelas
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table id="sortableTable" class="min-w-full bg-white text-sm">
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
                            Tingkat
                            <button class="sort-btn ml-1" data-column="2">⬍</button>
                        </th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">
                            Jumlah Siswa
                            <button class="sort-btn ml-1" data-column="3">⬍</button>
                        </th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($kelas as $k)
                        <tr class="border-b">
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ $k->nama_kelas }}</td>
                            <td class="py-3 px-4">{{ $k->tingkat }}</td>
                            <td class="py-3 px-4">{{ $k->siswas_count }}</td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.kelas.edit', $k->id) }}"
                                        class="bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-1 px-3 rounded-md text-sm transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?');">
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
                            <td colspan="5" class="text-center py-4">Tidak ada data kelas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
