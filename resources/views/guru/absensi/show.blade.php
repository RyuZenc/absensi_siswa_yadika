<x-app-layout>
    @section('header', 'Lakukan Absensi')

    <div class="mb-6 p-4 border rounded-lg">
        <h2 class="text-xl font-bold">{{ $jadwal->mapel->nama_mapel }} - {{ $jadwal->kelas->nama_kelas }}</h2>
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

            <form action="{{ route('guru.absensi.cancelCode', $sesiAbsen->id) }}" method="POST" class="mt-4">
                @csrf
                <button type="submit"
                    class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Batalkan
                    Kode</button>
            </form>
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

    <div>
        <h3 class="font-semibold text-lg mb-4">Absensi Manual</h3>
        <form action="{{ route('guru.absensi.storeManual', $sesiAbsen->id) }}" method="POST">
            @csrf
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
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
                                <td class="py-2 px-4">{{ $siswa->nama_lengkap }}</td>
                                @php $status = $absensiSudahAda[$siswa->id] ?? 'alpha'; @endphp
                                <td class="text-center"><input type="radio" name="absensi[{{ $siswa->id }}]"
                                        value="hadir" {{ $status == 'hadir' ? 'checked' : '' }}
                                        class="form-radio h-5 w-5 text-green-600"></td>
                                <td class="text-center"><input type="radio" name="absensi[{{ $siswa->id }}]"
                                        value="sakit" {{ $status == 'sakit' ? 'checked' : '' }}
                                        class="form-radio h-5 w-5 text-yellow-600"></td>
                                <td class="text-center"><input type="radio" name="absensi[{{ $siswa->id }}]"
                                        value="izin" {{ $status == 'izin' ? 'checked' : '' }}
                                        class="form-radio h-5 w-5 text-blue-600"></td>
                                <td class="text-center"><input type="radio" name="absensi[{{ $siswa->id }}]"
                                        value="alpha" {{ $status == 'alpha' ? 'checked' : '' }}
                                        class="form-radio h-5 w-5 text-red-600"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Simpan Absensi
                    Manual</button>
            </div>
            <div>
                <a href="{{ route('guru.absensi.export', $sesiAbsen->id) }}"
                    class="inline-block mb-4 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm py-2 px-4 rounded-lg shadow transition duration-200">
                    Export ke Excel
                </a>
            </div>
        </form>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const kodeContainer = document.getElementById('kode-aktif-container');
                const formContainer = document.getElementById('form-buat-kode-container');
                const countdownElement = document.getElementById('countdown-timer');

                function updateVisibility() {
                    const hasActiveCode = kodeContainer.style.display !== 'none';
                    if (hasActiveCode) {
                        formContainer.style.display = 'none';
                    } else {
                        formContainer.style.display = 'block';
                    }
                }

                if (kodeContainer && countdownElement) {
                    const waktuBerlakuData = kodeContainer.dataset.waktuBerlaku;
                    if (waktuBerlakuData) {
                        const waktuBerlaku = new Date(waktuBerlakuData).getTime();

                        const countdownInterval = setInterval(function() {
                            const sekarang = new Date().getTime();
                            const sisaWaktu = waktuBerlaku - sekarang;

                            if (sisaWaktu > 0) {
                                const menit = Math.floor((sisaWaktu % (1000 * 60 * 60)) / (1000 * 60));
                                const detik = Math.floor((sisaWaktu % (1000 * 60)) / 1000);
                                countdownElement.textContent =
                                    `Sisa Waktu: ${String(menit).padStart(2, '0')}:${String(detik).padStart(2, '0')}`;
                                kodeContainer.style.display = 'block';
                                updateVisibility();
                            } else {
                                clearInterval(countdownInterval);
                                countdownElement.textContent = 'Waktu habis!';
                                kodeContainer.style.display = 'none';
                                updateVisibility();
                            }
                        }, 1000);
                    } else {
                        kodeContainer.style.display = 'none';
                        updateVisibility();
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
