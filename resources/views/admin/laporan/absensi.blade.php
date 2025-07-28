<x-app-layout>
    @section('header', 'Laporan Absensi Harian')
    <form method="GET" class="mb-6 flex flex-col md:flex-row gap-4 items-stretch md:items-end">
        <div class="w-full md:w-auto">
            <label for="kelas_id" class="block text-sm font-medium">Kelas</label>
            <select name="kelas_id" id="kelas_id" class="rounded w-full md:w-48 border-gray-300">
                <option value="">-- Pilih Kelas --</option>
                @foreach ($kelasList as $k)
                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->tingkat . ' - ' . $k->nama_kelas }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="w-full md:w-auto">
            <label for="tanggal" class="block text-sm font-medium">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="rounded w-full md:w-48 border-gray-300"
                value="{{ request('tanggal') }}">
        </div>

        <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full md:w-auto">Preview</button>
            @if (request('kelas_id') && request('tanggal'))
                <a href="{{ route('admin.laporan.absensi.export', ['kelas_id' => request('kelas_id'), 'tanggal' => request('tanggal')]) }}"
                    class="bg-green-600 text-white px-4 py-2 rounded w-full md:w-auto text-center">Export Excel</a>
            @endif
        </div>
    </form>

    @if (count($sesiList) > 0)
        @php
            $mapelList = $sesiList->pluck('jadwal.mapel.nama_mapel')->unique()->sort()->values();
            $siswaList = collect();
            $absensiMap = [];

            foreach ($sesiList as $sesi) {
                $mapel = $sesi->jadwal->mapel->nama_mapel;

                foreach ($sesi->absensis as $absen) {
                    $nama = $absen->siswa->nama_lengkap;
                    $absensiMap[$nama][$mapel] = ucfirst($absen->status ?? '-');
                    $siswaList->put($nama, $absen->siswa);
                }
            }

            $sortedSiswa = $siswaList->sortKeys();
        @endphp

        <div class="overflow-auto bg-white shadow rounded border">
            <table class="min-w-max w-full text-xs sm:text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-200 text-center">
                        <th rowspan="2" class="border px-3 py-2">No</th>
                        <th rowspan="2" class="border px-3 py-2">Nama Siswa</th>
                        <th colspan="{{ $mapelList->count() }}" class="border px-3 py-2">Mata Pelajaran</th>
                    </tr>
                    <tr class="bg-gray-100 text-center">
                        @foreach ($mapelList as $mapel)
                            <th class="border px-3 py-2 whitespace-nowrap">{{ $mapel }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sortedSiswa->keys() as $index => $nama)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="border px-3 py-2 text-center">{{ $index + 1 }}</td>
                            <td class="border px-3 py-2 whitespace-nowrap">{{ $nama }}</td>
                            @foreach ($mapelList as $mapel)
                                <td class="border px-3 py-2 text-center">
                                    {{ $absensiMap[$nama][$mapel] ?? '-' }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500 mt-4">Tidak ada data absensi untuk tanggal dan kelas yang dipilih.</p>
    @endif
</x-app-layout>
