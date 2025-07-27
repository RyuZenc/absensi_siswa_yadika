<table>
    <thead>
        <tr>
            <th colspan="{{ 3 + count($dates) }}" style="font-weight: bold; font-size: 14px; text-align: center;">
                REKAPITULASI ABSENSI SISWA</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold;">Mata Pelajaran</th>
            <th colspan="2">: {{ $filterInfo['mapel'] }}</th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Kelas</th>
            <th colspan="2">: {{ $kelas->tingkat . ' - ' . $kelas->nama_kelas }}</th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Guru</th>
            <th colspan="2">: {{ $filterInfo['guru'] }}</th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Periode</th>
            <th colspan="2">: {{ $filterInfo['periode'] }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold; border: 1px solid black; text-align: center;">No</th>
            <th style="font-weight: bold; border: 1px solid black; text-align: center;">Nama Siswa</th>
            <th style="font-weight: bold; border: 1px solid black; text-align: center;">NIS</th>
            @foreach ($dates as $date)
                <th style="font-weight: bold; border: 1px solid black; text-align: center;">{{ $date }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($students as $student)
            <tr>
                <td style="border: 1px solid black; text-align: center;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid black;">{{ $student['nama_lengkap'] }}</td>
                <td style="border: 1px solid black;">{{ $student['nis'] }}</td>
                @foreach ($student['absensi'] as $status)
                    <td style="border: 1px solid black; text-align: center;">{{ $status }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
