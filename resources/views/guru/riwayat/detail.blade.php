<x-app-layout>
    @section('header', 'Detail Absensi')

    <div class="mb-4 p-4 bg-white rounded-lg shadow">
        <h2 class="text-xl font-bold mb-2">Detail Absensi</h2>
        <p><strong>Mapel:</strong> {{ $sesi->jadwal->mapel->nama_mapel }}</p>
        <p><strong>Kelas:</strong> {{ $sesi->jadwal->kelas->tingkat }} - {{ $sesi->jadwal->kelas->nama_kelas }}</p>
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('l, d F Y') }}</p>
        <p><strong>Jam:</strong> {{ $sesi->jadwal->jam_mulai }} - {{ $sesi->jadwal->jam_selesai }}</p>
        <p>
            <strong>Hadir:</strong> {{ $absensiCounts['hadir'] }} |
            <strong>Sakit:</strong> {{ $absensiCounts['sakit'] }} |
            <strong>Izin:</strong> {{ $absensiCounts['izin'] }} |
            <strong>Alpha:</strong> {{ $absensiCounts['alpha'] }}
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded-lg shadow">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Nama Siswa</th>
                    <th class="px-4 py-2">NIS</th>
                    <th class="px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sesi->absensis as $absen)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $absen->siswa->nama_lengkap }}</td>
                        <td class="px-4 py-2">{{ $absen->siswa->nis }}</td>
                        <td class="px-4 py-2 capitalize">{{ $absen->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
