<x-app-layout>
    @section('header', 'Manajemen Siswa')

    <div class="w-full">
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.siswa.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">+ Tambah Siswa</a>
        </div>
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama Lengkap</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">NIS</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Email</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Kelas</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($siswas as $siswa)
                        <tr class="border-b">
                            <td class="py-3 px-4">{{ $siswa->nama_lengkap }}</td>
                            <td class="py-3 px-4">{{ $siswa->nis }}</td>
                            <td class="py-3 px-4">{{ $siswa->user->email }}</td>
                            <td class="py-3 px-4">{{ $siswa->kelas->nama_kelas }}</td>
                            <td class="py-3 px-4 flex items-center">
                                <a href="{{ route('admin.siswa.edit', $siswa->id) }}"
                                    class="text-yellow-500 hover:text-yellow-700 mr-2">Edit</a>
                                <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Tidak ada data siswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
