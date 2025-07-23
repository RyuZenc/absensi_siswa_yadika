<!DOCTYPE html>
<html>

<head>
    <title>Laporan Absensi Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .info {
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2,
        h3 {
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Absensi Siswa</h2>
        <h3>SMK Yadika Soreang</h3>
    </div>

    <div class="info">
        <p><strong>Mata Pelajaran:</strong> {{ $sesiAbsen->jadwal->mapel->nama_mapel }}</p>
        <p><strong>Kelas:</strong> {{ $sesiAbsen->jadwal->kelas->nama_kelas }}</p>
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($sesiAbsen->tanggal)->isoFormat('dddd, D MMMM YYYY') }}
        </p>
        <p><strong>Waktu:</strong> {{ date('H:i', strtotime($sesiAbsen->jadwal->jam_mulai)) }} -
            {{ date('H:i', strtotime($sesiAbsen->jadwal->jam_selesai)) }}</p>
        <p><strong>Guru Pengajar:</strong> {{ $sesiAbsen->guru->nama_lengkap }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Status Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absensis as $index => $absensi)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $absensi['nama_siswa'] }}</td>
                    <td>{{ ucfirst($absensi['status']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
