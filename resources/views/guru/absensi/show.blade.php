<x-app-layout>
    @section('header', 'Lakukan Absensi')
    <div class="mb-6 p-4 border rounded-lg">
        <h2 class="text-xl font-bold">
            {{ $jadwal->mapel->nama_mapel }} - {{ $jadwal->kelas->tingkat }} {{ $jadwal->kelas->nama_kelas }}
        </h2>
        <p class="text-gray-600">{{ $jadwal->hari }}, {{ date('H:i', strtotime($jadwal->jam_mulai)) }} -
            {{ date('H:i', strtotime($jadwal->jam_selesai)) }}</p>
    </div>
    <div class="mb-8 p-4 bg-blue-50 rounded-lg">
        <h3 class="font-semibold text-lg mb-2">Absensi dengan Kode</h3>
        <div id="kode-aktif-container" @if (!$sesiAbsen->kode_absen || \Carbon\Carbon::now()->isAfter($sesiAbsen->berlaku_hingga)) style="display: none;" @endif
            data-waktu-berlaku="{{ $sesiAbsen->berlaku_hingga->toIso8601String() }}">
            <p class="text-gray-700">Kode yang sedang aktif:</p>
            <p class="text-4xl font-mono font-bold text-center my-4 p-4 bg-white rounded tracking-widest">
                {{ $sesiAbsen->kode_absen }}
            </p>
            <p id="countdown-timer" class="text-base text-center text-gray-700 font-semibold">
            </p>
        </div>
        <div id="form-buat-kode-container" @if ($sesiAbsen->kode_absen && \Carbon\Carbon::now()->isBefore($sesiAbsen->berlaku_hingga)) style="display: none;" @endif>
            <p class="text-gray-700 mb-2">Buat kode unik agar siswa dapat melakukan absensi mandiri.</p>
            <form action="{{ route('guru.absensi.createCode', $sesiAbsen->id) }}" method="POST"
                class="flex items-end space-x-4">
                @csrf
                <div>
                    <label for="durasi" class="block text-sm font-medium text-gray-700">Durasi (menit)</label>
                    <input type="number" name="durasi" id="durasi" value="15" min="1" max="60"
                        class="w-24 mt-1 rounded-md border-gray-300 shadow-sm text-center">
                    @error('durasi')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit"
                    class="flex-grow bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Buat
                    Kode</button>
            </form>
        </div>
    </div>
    <div class="mb-4 flex justify-end">
        <a href="{{ route('guru.absensi.export', $sesiAbsen->id) }}"
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-block">
            Export Absensi ke Excel
        </a>
    </div>
    <div>
        <x-confirm-modal name="hadirkan-semua"
            message="Anda yakin ingin menandai semua siswa sebagai 'Hadir'? Ini akan mengubah status absensi semua siswa." />
        <button type="button" id="hadirkan-semua-btn"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 m-2 rounded mb-4">
            Hadirkan Semua
        </button>
        <h3 class="font-semibold text-lg mb-4">Absensi Manual</h3>
        <form action="{{ route('guru.absensi.storeManual', $sesiAbsen->id) }}" method="POST">
            @csrf
            <p>
                <strong>Hadir:</strong> {{ $absensiCounts['hadir'] }} |
                <strong>Sakit:</strong> {{ $absensiCounts['sakit'] }} |
                <strong>Izin:</strong> {{ $absensiCounts['izin'] }} |
                <strong>Alpha:</strong> {{ $absensiCounts['alpha'] }}
            </p>
            <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="text-left py-2 px-4">No</th>
                            <th class="text-left py-2 px-4">NIS</th>
                            <th class="text-left py-2 px-4">Nama Siswa</th>
                            <th class="text-center py-2 px-4">Hadir</th>
                            <th class="text-center py-2 px-4">Sakit</th>
                            <th class="text-center py-2 px-4">Izin</th>
                            <th class="text-center py-2 px-4">Alpha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($siswas as $siswa)
                            <tr class="border-b">
                                <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                <td class="py-2 px-4">{{ $siswa->nis }}</td>
                                <td class="py-2 px-4">{{ $siswa->nama_lengkap }}</td>
                                @php $status = $absensiSudahAda[$siswa->id] ?? null; @endphp
                                @foreach (['hadir' => 'green', 'sakit' => 'yellow', 'izin' => 'blue', 'alpha' => 'red'] as $value => $color)
                                    <td class="text-center">
                                        <input type="radio" name="absensi[{{ $siswa->id }}]"
                                            value="{{ $value }}"
                                            class="form-radio h-5 w-5 text-{{ $color }}-600 absensi-radio"
                                            data-siswa-id="{{ $siswa->id }}" data-status="{{ $value }}"
                                            {{ $status == $value ? 'checked' : '' }}>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    @push('scripts')
        <script>
            // Configuration for guru absensi
            window.guruAbsensiConfig = {
                updateUrl: "{{ route('guru.absensi.updateStatus') }}",
                sesiAbsenId: {{ $sesiAbsen->id }}
            };
        </script>
        @vite(['resources/js/guru-absensi.js'])
    @endpush
</x-app-layout>
