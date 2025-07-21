<x-app-layout>
    @section('header', 'Manajemen Mata Pelajaran')

    <div class="w-full">
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.mapel.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition">
                + Tambah Mata Pelajaran
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="w-full px-4 bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="w-4/5 text-left py-3 px-4 uppercase font-semibold text-sm">Nama Mata Pelajaran</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($mapels as $mapel)
                        <tr class="border-b">
                            <td class="text-left py-3 px-4">{{ $mapel->nama_mapel }}</td>
                            <td class="text-left py-3 px-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.mapel.edit', $mapel->id) }}"
                                        class="bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-1 px-3 rounded-md text-sm transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.mapel.destroy', $mapel->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?');">
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
                            <td colspan="2" class="text-center py-4">Tidak ada data mata pelajaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
