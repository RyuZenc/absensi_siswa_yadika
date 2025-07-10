<x-app-layout>
    @section('header', 'Manajemen Guru')

    <div class="w-full">
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.guru.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded transition-all border border-gray-200">
                + Tambah Guru
            </a>
        </div>
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="w-full px-4 bg-white">
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
                            <td class="text-left py-3 px-4 d-flex align-items-center gap-2">
                                <a href="{{ route('admin.guru.edit', $guru->id) }}"
                                    class="btn btn-warning btn-sm rounded-md">
                                    Edit
                                </a>
                                <form action="{{ route('admin.guru.destroy', $guru->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data guru ini?');">
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
                            <td colspan="4" class="text-center py-4">Tidak ada data guru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
