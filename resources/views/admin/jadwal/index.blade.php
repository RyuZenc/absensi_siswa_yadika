<x-app-layout>
    @section('header', 'Daftar Jadwal Pelajaran')

    <div class="mb-4 text-right">
        <a href="{{ route('admin.jadwal.create') }}"
            class="text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md text-sm transition w-full sm:w-auto">
            Tambah Jadwal
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-3">
        @forelse ($jadwals as $kelas_id => $jadwalPerKelas)
            <div x-data="{ open: false }" class="mb-4 border rounded-lg shadow-sm">
                <button @click="open = !open"
                    class="w-full px-4 py-3 bg-gray-100 font-semibold text-gray-700 flex justify-between items-center rounded-t-lg">
                    <span>
                        {{ $jadwalPerKelas->first()->kelas->tingkat }} -
                        {{ $jadwalPerKelas->first()->kelas->nama_kelas }}
                    </span>
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                </button>

                <div x-show="open" x-collapse class="overflow-x-auto bg-white rounded-b-lg border-t border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Hari</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Mata Pelajaran</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Jam Mulai</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Jam Selesai</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Guru Jadwal</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @php
                                $prevHari = null;
                            @endphp

                            @foreach ($jadwalPerKelas as $jadwal)
                                @php
                                    $isNewDay = $prevHari !== $jadwal->hari;
                                    $prevHari = $jadwal->hari;
                                @endphp
                                <tr class="@if ($isNewDay) border-t-4 border-blue-500 @endif">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $jadwal->hari }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $jadwal->mapel->nama_mapel }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $jadwal->guru->nama_lengkap }}</td>
                                    <td class="py-3 px-4">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}"
                                                class="bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-1 px-3 rounded-md text-sm transition">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data jadwal ini?');">
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500">Tidak ada jadwal yang tersedia.</p>
        @endforelse
    </div>
</x-app-layout>
