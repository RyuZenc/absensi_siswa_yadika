<x-app-layout>
    @section('header', 'Manajemen Kelas')

    <div class="w-full">
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.kelas.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">+ Tambah Kelas</a>
        </div>
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">Nama Kelas</th>
                        <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">Tingkat</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Jumlah Siswa</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($kelas as $k)
                        <tr class="border-b">
                            <td class="text-left py-3 px-4">{{ $k->nama_kelas }}</td>
                            <td class="text-left py-3 px-4">{{ $k->tingkat }}</td>
                            <td class="text-left py-3 px-4">{{ $k->siswas_count }}</td>
                            <td class="text-left py-3 px-4 flex items-center">
                                <a href="{{ route('admin.kelas.edit', $k->id) }}"
                                    class="text-yellow-500 hover:text-yellow-700 mr-2">Edit</a>
                                <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Tidak ada data kelas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
