<x-app-layout>
    @section('header', 'Lakukan Absensi')

    <div class="mb-6 p-4 border rounded-lg">
        <h2 class="text-xl font-bold">{{ $jadwal->mapel->nama_mapel }} - {{ $jadwal->kelas->nama_kelas }}</h2>
        <p class="text-gray-600">{{ $jadwal->hari }}, {{ date('H:i', strtotime($jadwal->jam_mulai)) }} -
            {{ date('H:i', strtotime($jadwal->jam_selesai)) }}</p>
    </div>

    <!-- Opsi Absensi Kode -->
    <div class="mb-8 p-4 bg-blue-50 rounded-lg">
        <h3 class="font-semibold text-lg mb-2">Absensi dengan Kode</h3>
        @if ($sesiAbsen->kode_absen)
            <p class="text-gray-700">Bagikan kode ini kepada siswa:</p>
            <p class="text-4xl font-mono font-bold text-center my-4 p-4 bg-white rounded tracking-widest">
                {{ $sesiAbsen->kode_absen }}</p>
            <p class="text-sm text-center text-gray-600">Kode berlaku hingga:
                {{ $sesiAbsen->berlaku_hingga->format('H:i:s') }}</p>
        @else
            <p class="text-gray-700 mb-2">Buat kode unik agar siswa dapat melakukan absensi mandiri.</p>
            <form action="{{ route('guru.absensi.createCode', $sesiAbsen->id) }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Buat
                    Kode</button>
            </form>
        @endif
    </div>

    <!-- Opsi Absensi Manual -->
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
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Simpan Absensi
                    Manual</button>
            </div>
        </form>
    </div>
</x-app-layout>
