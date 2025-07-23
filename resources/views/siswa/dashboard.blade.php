<x-app-layout>
    @section('header', 'Dashboard Siswa')
    <h2 class="font-semibold mb-6">Selamat Datang, {{ Auth::user()->name }}!</h2>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <div>
                <h2 class="text-xl font-semibold mb-4">Jadwal Pelajaran Hari Ini</h2>
                <div class="bg-gray-50 p-4 rounded-lg shadow-inner">
                    <ul class="space-y-3">

                        @forelse ($jadwalHariIni ?? [] as $jadwal)
                            <li class="flex justify-between items-center p-3 bg-white rounded-md shadow-sm">
                                <div>
                                    <p class="font-bold">{{ $jadwal->mapel->nama_mapel }}</p>
                                    <p class="text-sm text-gray-600">{{ $jadwal->guru->nama_lengkap }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-mono text-sm">{{ date('H:i', strtotime($jadwal->jam_mulai)) }} -
                                        {{ date('H:i', strtotime($jadwal->jam_selesai)) }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="text-center text-gray-500 p-4">Tidak ada jadwal pelajaran hari ini.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4">Riwayat Absensi Terakhir</h2>
                <div class="overflow-x-auto bg-white rounded-lg shadow-inner">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="text-left py-2 px-4 font-semibold text-sm">Tanggal</th>
                                <th class="text-left py-2 px-4 font-semibold text-sm">Mata Pelajaran</th>
                                <th class="text-left py-2 px-4 font-semibold text-sm">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($riwayatAbsensi ?? [] as $absensi)
                                <tr class="border-b">
                                    <td class="py-2 px-4">
                                        {{ \Carbon\Carbon::parse($absensi->tanggal)->isoFormat('D MMM YYYY') }}</td>
                                    <td class="py-2 px-4">{{ $absensi->sesiAbsen->jadwal->mapel->nama_mapel }}</td>
                                    <td class="py-2 px-4">
                                        @if ($absensi->status == 'hadir')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Hadir</span>
                                        @elseif($absensi->status == 'sakit')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Sakit</span>
                                        @elseif($absensi->status == 'izin')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Izin</span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Alpha</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-gray-500">Belum ada riwayat absensi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="sticky top-10">
                <h2 class="text-xl font-semibold mb-4 text-center">Absensi Sekarang</h2>
                <div class="bg-blue-50 p-6 rounded-lg shadow-md">
                    <p class="text-center text-gray-600 mb-6">Masukkan kode yang diberikan oleh guru Anda.</p>

                    @if ($sesiAbsenHariIni && $sesiAbsenHariIni->kode_absen)
                        @if (\Carbon\Carbon::now()->isAfter($sesiAbsenHariIni->berlaku_hingga))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                                role="alert">
                                <strong class="font-bold">Perhatian!</strong>
                                <span class="block sm:inline">Kode absensi ini sudah kadaluarsa. Silakan hubungi guru
                                    Anda untuk instruksi lebih lanjut.</span>
                            </div>
                        @endif

                        <form action="{{ route('siswa.absensi.store') }}" method="POST">
                            @csrf
                            <div>
                                <label for="kode_absen" class="sr-only">Kode Absen</label>
                                <input type="text" name="kode_absen" id="kode_absen"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-center text-3xl uppercase tracking-widest font-mono"
                                    maxlength="6" required placeholder="KODE">
                                @error('kode_absen')
                                    <span class="text-red-500 text-sm block text-center mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mt-6">
                                <button type="submit"
                                    class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded transition duration-300"
                                    @if (\Carbon\Carbon::now()->isAfter($sesiAbsenHariIni->berlaku_hingga)) disabled @endif> {{-- Tombol dinonaktifkan jika kode kadaluarsa --}}
                                    Kirim Absensi
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative"
                            role="alert">
                            <strong class="font-bold">Informasi:</strong>
                            <span class="block sm:inline">Belum ada sesi absensi dengan kode aktif untuk kelas Anda hari
                                ini.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
