<table>
    <thead>
        <tr>
            <th colspan="{{ 3 + count($dates) }}" style="font-weight: bold; font-size: 14px; text-align: center;">
                REKAPITULASI ABSENSI SISWA</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold;">Kelas</th>
            <th colspan="2">: {{ $kelas->tingkat . ' - ' . $kelas->nama_kelas }}</th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Mata Pelajaran</th>
            <th colspan="2">: {{ $mapel->nama_mapel }}</th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Wali Kelas</th>
            <th colspan="2">: {{ $kelas->guru->nama_lengkap ?? 'N/A' }}</th>
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
        @foreach ($rekapData as $index => $data)
            <tr>
                <td style="border: 1px solid black; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid black;">{{ $data['nama_lengkap'] }}</td>
                <td style="border: 1px solid black;">{{ $data['nis'] }}</td>
                @foreach ($data['absensi'] as $status)
                    <td style="border: 1px solid black; text-align: center;">{{ $status }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
