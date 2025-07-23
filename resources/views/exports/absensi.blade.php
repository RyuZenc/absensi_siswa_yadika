<table>
    <thead>
        <tr>
            <th colspan="3">
                Absensi: {{ $jadwal->mapel->nama_mapel }} -
                {{ $jadwal->kelas->nama_kelas }} ({{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }})
            </th>
        </tr>
        <tr>
            <th>Nama Siswa</th>
            <th>Status</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($absensis as $absen)
            <tr>
                <td>{{ $absen->siswa->nama_lengkap }}</td>
                <td>{{ ucfirst($absen->status) }}</td>
                <td>{{ $absen->keterangan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
