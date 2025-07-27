<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rekap Absensi Mengajar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" x-data="{ range: '{{ old('range', $inputs['range'] ?? 'this_week') }}' }">

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Filter Rekapitulasi</h3>

                    {{-- Form Filter --}}
                    <form action="{{ route('guru.rekap.index') }}" method="GET" class="pb-4 border-b">
                        {{-- (Form filter tetap sama seperti sebelumnya) --}}
                        <input type="hidden" name="filter" value="true">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Filter Mapel -->
                            <div>
                                <label for="mapel_id" class="block font-medium text-sm text-gray-700">Mata
                                    Pelajaran</label>
                                <select name="mapel_id" id="mapel_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach ($mapels as $mapel)
                                        <option value="{{ $mapel->id }}"
                                            {{ old('mapel_id', $inputs['mapel_id'] ?? '') == $mapel->id ? 'selected' : '' }}>
                                            {{ $mapel->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Kelas -->
                            <div>
                                <label for="kelas_id" class="block font-medium text-sm text-gray-700">Kelas</label>
                                <select name="kelas_id" id="kelas_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelasList as $kelas)
                                        <option value="{{ $kelas->id }}"
                                            {{ old('kelas_id', $inputs['kelas_id'] ?? '') == $kelas->id ? 'selected' : '' }}>
                                            {{ $kelas->tingkat . ' - ' . $kelas->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Range -->
                            <div>
                                <label for="range" class="block font-medium text-sm text-gray-700">Rentang
                                    Waktu</label>
                                <select name="range" id="range" x-model="range"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="this_week">Minggu Ini</option>
                                    <option value="this_month">Bulan Ini</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                        </div>

                        <!-- Custom Date Range (muncul jika 'Custom' dipilih) -->
                        <div x-show="range === 'custom'" class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                            <div>
                                <label for="start_date" class="block font-medium text-sm text-gray-700">Tanggal
                                    Mulai</label>
                                <input type="date" name="start_date" id="start_date"
                                    value="{{ old('start_date', $inputs['start_date'] ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label for="end_date" class="block font-medium text-sm text-gray-700">Tanggal
                                    Selesai</label>
                                <input type="date" name="end_date" id="end_date"
                                    value="{{ old('end_date', $inputs['end_date'] ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 mt-6">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Tampilkan Rekap
                            </button>
                            @if (!empty($rekapData))
                                <a href="{{ route('guru.rekap.export', $inputs) }}"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                    Export ke Excel
                                </a>
                            @endif
                        </div>
                    </form>

                    {{-- Hasil Rekap --}}
                    @if (!empty($rekapData))
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold">Hasil Rekapitulasi Absensi</h3>
                            <div class="text-sm text-gray-600 mt-1 mb-4">
                                <p><strong>Mata Pelajaran:</strong> {{ $filterInfo['mapel'] }}</p>
                                <p><strong>Kelas:</strong> {{ $kelas->tingkat . ' - ' . $kelas->nama_kelas }}</p>
                                <p><strong>Guru:</strong> {{ $filterInfo['guru'] }}</p>
                                <p><strong>Periode:</strong> {{ $filterInfo['periode'] }}</p>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">
                                                No</th>
                                            <th
                                                class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">
                                                Nama Siswa</th>
                                            <th
                                                class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">
                                                NIS</th>
                                            @foreach ($dates as $date)
                                                <th
                                                    class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">
                                                    {{ $date }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($rekapData as $data)
                                            <tr>
                                                <td class="px-3 py-2 whitespace-nowrap text-sm border">
                                                    {{ $loop->iteration }}</td>
                                                <td
                                                    class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900 border">
                                                    {{ $data['nama_lengkap'] }}</td>
                                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 border">
                                                    {{ $data['nis'] }}</td>
                                                @foreach ($data['absensi'] as $status)
                                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-center border">
                                                        {{ $status }}</td>
                                                @endforeach
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ 3 + count($dates) }}"
                                                    class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data
                                                    absensi untuk filter yang dipilih.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @elseif(request()->has('filter'))
                        <div class="mt-8 text-center text-gray-500 py-10">
                            <p>Tidak ada data yang ditemukan untuk filter yang dipilih.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
