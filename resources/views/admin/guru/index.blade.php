<x-app-layout>
    @section('header', 'Manajemen Guru')

    <div class="w-full">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
            <div class="flex-1 min-w-[260px]">
                <form action="{{ route('admin.guru.index') }}" method="GET" class="flex gap-2">
                    <input type="text" name="search" placeholder="Cari nama, NIP, atau email..."
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ $search ?? '' }}">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition">
                        Cari
                    </button>
                </form>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 bg-white p-2 rounded-lg shadow-md">
                    <form action="{{ route('admin.guru.import') }}" method="POST" enctype="multipart/form-data"
                        class="flex items-center gap-2">
                        @csrf
                        <input type="file" name="file"
                            class="text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-green-50 file:text-green-700
                            hover:file:bg-green-100"
                            required>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-1.5 px-4 rounded-md text-sm transition">
                            Import
                        </button>
                    </form>
                </div>
                <div>
                    <a href="{{ route('admin.guru.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition">
                        + Tambah Guru
                    </a>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table id="sortableTable" class="w-full px-4 bg-white text-sm">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">
                            #
                            <button class="sort-btn ml-1" data-column="0">⬍</button>
                        </th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">
                            Nama Lengkap
                            <button class="sort-btn ml-1" data-column="1">⬍</button>
                        </th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">
                            NIP
                            <button class="sort-btn ml-1" data-column="2">⬍</button>
                        </th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">
                            Username
                            <button class="sort-btn ml-1" data-column="3">⬍</button>
                        </th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">
                            Email
                            <button class="sort-btn ml-1" data-column="4">⬍</button>
                        </th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($gurus as $guru)
                        <tr class="border-b">
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ $guru->nama_lengkap }}</td>
                            <td class="py-3 px-4">{{ $guru->nip }}</td>
                            <td class="py-3 px-4">{{ $guru->user->username ?? '-' }}</td>
                            <td class="py-3 px-4">{{ $guru->user->email }}</td>
                            <td class="text-left py-3 px-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.guru.edit', $guru->id) }}"
                                        class="bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-1 px-3 rounded-md text-sm transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.guru.destroy', $guru->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data guru ini?');">
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
                            <td colspan="6" class="text-center py-4">Tidak ada data guru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $gurus->links() }}
        </div>
    </div>
</x-app-layout>
