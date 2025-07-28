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
            <!--
            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Simpan Absensi
                    Manual</button>
            </div>
            -->
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const kodeContainer = document.getElementById('kode-aktif-container');
                const formContainer = document.getElementById('form-buat-kode-container');
                const countdownElement = document.getElementById('countdown-timer');

                if (kodeContainer && countdownElement && kodeContainer.style.display !== 'none') {
                    const waktuBerlaku = new Date(kodeContainer.dataset.waktuBerlaku).getTime();

                    const countdownInterval = setInterval(function() {
                        const sekarang = new Date().getTime();
                        const sisaWaktu = waktuBerlaku - sekarang;

                        if (sisaWaktu > 0) {
                            const menit = Math.floor((sisaWaktu % (1000 * 60 * 60)) / (1000 * 60));
                            const detik = Math.floor((sisaWaktu % (1000 * 60)) / 1000);
                            countdownElement.textContent =
                                `Sisa Waktu: ${String(menit).padStart(2, '0')}:${String(detik).padStart(2, '0')}`;
                        } else {
                            clearInterval(countdownInterval);
                            countdownElement.textContent = 'Waktu habis!';

                            kodeContainer.style.display = 'none';
                            formContainer.style.display = 'block';
                        }
                    }, 1000);
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                const radios = document.querySelectorAll('.absensi-radio');

                radios.forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        const siswaId = this.dataset.siswaId;
                        const status = this.dataset.status;

                        fetch("{{ route('guru.absensi.updateStatus') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                },
                                body: JSON.stringify({
                                    siswa_id: siswaId,
                                    status: status,
                                    sesi_absen_id: {{ $sesiAbsen->id }}
                                }),
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Sukses update absensi:', data);
                            })
                            .catch(error => {
                                console.error('Gagal update absensi:', error);
                            });
                    });
                });
            });

            // Hadirkan semua siswa dengan konfirmasi
            document.getElementById('hadirkan-semua-btn').addEventListener('click', () => {
                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: 'hadirkan-semua'
                }));
            });

            window.addEventListener('confirmed', (e) => {
                if (e.detail.modal === 'hadirkan-semua') {
                    const semuaRadioHadir = document.querySelectorAll('input[type="radio"][value="hadir"]');
                    const fetchPromises = []; // Definisikan array untuk menyimpan promise

                    semuaRadioHadir.forEach(radio => {
                        if (!radio.checked) {
                            radio.checked = true;

                            // Ambil info siswa
                            const siswaId = radio.dataset.siswaId;
                            const status = radio.value;

                            // Kirim request dan simpan promise-nya
                            const promise = fetch("{{ route('guru.absensi.updateStatus') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                },
                                body: JSON.stringify({
                                    siswa_id: siswaId,
                                    status: status,
                                    sesi_absen_id: {{ $sesiAbsen->id }}
                                }),
                            });

                            fetchPromises.push(promise);
                        }
                    });

                    // Tunggu semua promise selesai, lalu reload halaman
                    Promise.all(fetchPromises)
                        .then(() => {
                            console.log('Semua absensi berhasil diperbarui');
                            location.reload();
                        })
                        .catch(error => {
                            console.error('Gagal memperbarui absensi:', error);
                            // Opsi: Tampilkan pesan error kepada pengguna
                        });
                }
            });
        </script>
    @endpush
</x-app-layout>
