<x-app-layout>
    @section('header', 'Manajemen Siswa')

    <div class="w-full">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">

            <div class="flex-1 min-w-[260px]">
                <form action="{{ route('admin.siswa.index') }}" method="GET" class="flex gap-2">
                    <input type="text" name="search" placeholder="Cari nama, NIS, email, username, atau kelas..."
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                        value="{{ $search ?? '' }}">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md text-sm transition">
                        Cari
                    </button>
                </form>
            </div>
            <div class="flex items-center gap-2 bg-white p-2 rounded-lg shadow">
                <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data"
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
                <a href="{{ route('admin.siswa.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md text-sm transition">
                    + Tambah Siswa
                </a>
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
                            NIS
                            <button class="sort-btn ml-1" data-column="2">⬍</button>
                        </th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">
                            Email
                            <button class="sort-btn ml-1" data-column="3">⬍</button>
                        </th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">
                            Kelas
                            <button class="sort-btn ml-1" data-column="4">⬍</button>
                        </th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($siswas as $index => $siswa)
                        <tr class="border-b">
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ $siswa->nama_lengkap }}</td>
                            <td class="py-3 px-4">{{ $siswa->nis }}</td>
                            <td class="py-3 px-4">{{ $siswa->user->email }}</td>
                            <td class="py-3 px-4">{{ $siswa->kelas->tingkat . ' - ' . $siswa->kelas->nama_kelas }}</td>
                            <td class="py-3 px-4 flex gap-2">
                                <a href="{{ route('admin.siswa.edit', $siswa->id) }}"
                                    class="bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-1 px-3 rounded-md text-sm transition">
                                    Edit
                                </a>
                                <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="search" value="{{ $search ?? '' }}">
                                    <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-1 px-3 rounded-md text-sm transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Tidak ada data siswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $siswas->links() }}
        </div>
    </div>
</x-app-layout>
