<x-app-layout>
    @section('header', 'Manajemen Guru')

    <div class="w-full">
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.guru.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Tambah Guru
            </a>
        </div>
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama Lengkap</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">NIP</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Email</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($gurus as $guru)
                        <tr class="border-b">
                            <td class="py-3 px-4">{{ $guru->nama_lengkap }}</td>
                            <td class="py-3 px-4">{{ $guru->nip }}</td>
                            <td class="py-3 px-4">{{ $guru->user->email }}</td>
                            <td class="py-3 px-4 flex items-center">
                                <a href="{{ route('admin.guru.edit', $guru->id) }}"
                                    class="text-yellow-500 hover:text-yellow-700 mr-2">Edit</a>
                                <form action="{{ route('admin.guru.destroy', $guru->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data guru ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Tidak ada data guru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
