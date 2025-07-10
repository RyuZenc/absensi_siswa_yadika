<x-app-layout>
    @section('header', 'Manajemen Jadwal')

    <div class="w-full">
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.jadwal.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded transition-all border border-gray-200">
                + Tambah Jadwal
            </a>
        </div>
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="w-full px-4 bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Hari</th>
                        <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Jam</th>
                        <th class="w-1/6 text-left py-3 px-4 uppercase font-semibold text-sm">Kelas</th>
                        <th class="w-2/6 text-left py-3 px-4 uppercase font-semibold text-sm">Mata Pelajaran</th>
                        <th class="w-2/6 text-left py-3 px-4 uppercase font-semibold text-sm">Guru</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($jadwals as $jadwal)
                        <tr class="border-b">
                            <td class="text-left py-3 px-4">{{ $jadwal->hari }}</td>
                            <td class="text-left py-3 px-4">{{ date('H:i', strtotime($jadwal->jam_mulai)) }} -
                                {{ date('H:i', strtotime($jadwal->jam_selesai)) }}</td>
                            <td class="text-left py-3 px-4">{{ $jadwal->kelas->nama_kelas }}</td>
                            <td class="text-left py-3 px-4">{{ $jadwal->mapel->nama_mapel }}</td>
                            <td class="text-left py-3 px-4">{{ $jadwal->guru->nama_lengkap }}</td>
                            <td class="text-left py-3 px-4 d-flex align-items-center gap-2">
                                <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}"
                                    class="btn btn-warning btn-sm rounded-md">
                                    Edit
                                </a>
                                <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
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
                            <td colspan="6" class="text-center py-4">Tidak ada data jadwal.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
