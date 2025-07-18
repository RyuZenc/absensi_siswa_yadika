<x-app-layout>
    @section('header', 'Manajemen Kelas')

    <div class="w-full">
        <div class="flex justify-end mb-4 ">
            <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary font-bold py-2 px-4 rounded-md">
                + Tambah Kelas
            </a>
        </div>
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="w-full px-4 bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="w-1/3 py-3 px-4 uppercase font-semibold text-sm">Nama Kelas</th>
                        <th class="w-1/3 py-3 px-4 uppercase font-semibold text-sm">Tingkat</th>
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
                            <td class="text-left py-3 px-4 d-flex align-items-center gap-2">
                                <a href="{{ route('admin.kelas.edit', $k->id) }}"
                                    class="btn btn-warning btn-sm rounded-md">
                                    Edit
                                </a>
                                <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-md">
                                        Hapus
                                    </button>
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
