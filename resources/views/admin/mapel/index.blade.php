<x-app-layout>
    @section('header', 'Manajemen Mata Pelajaran')

    <div class="w-full">
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.mapel.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Tambah Mapel
            </a>
        </div>
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full bg-white">
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
                            <td class="text-left py-3 px-4 flex items-center">
                                <a href="{{ route('admin.mapel.edit', $mapel->id) }}"
                                    class="text-yellow-500 hover:text-yellow-700 mr-2">Edit</a>
                                <form action="{{ route('admin.mapel.destroy', $mapel->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                                </form>
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
