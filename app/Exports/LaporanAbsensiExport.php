<?php

namespace App\Exports;

use App\Models\SesiAbsen;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanAbsensiExport implements FromArray, ShouldAutoSize, WithEvents
{
    protected $kelasId;
    protected $tanggal;
    protected $kelasNama;
    protected $hariTanggal;
    protected $mapelList = [];

    public function __construct($kelasId, $tanggal)
    {
        $this->kelasId = $kelasId;
        $this->tanggal = $tanggal;

        $sesi = SesiAbsen::with('jadwal.kelas')
            ->whereDate('tanggal', $tanggal)
            ->whereHas('jadwal', fn($q) => $q->where('kelas_id', $kelasId))
            ->first();

        $this->kelasNama = $sesi?->jadwal?->kelas?->tingkat . ' ' . $sesi?->jadwal?->kelas?->nama_kelas ?? 'Tidak diketahui';
        $this->hariTanggal = Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d F Y');
    }

    public function array(): array
    {
        $sesiList = SesiAbsen::with(['jadwal.mapel', 'absensis.siswa'])
            ->whereDate('tanggal', $this->tanggal)
            ->whereHas('jadwal', fn($q) => $q->where('kelas_id', $this->kelasId))
            ->get();

        $mapelNames = [];
        $siswaNames = [];
        $absensiData = [];

        foreach ($sesiList as $sesi) {
            $mapel = $sesi->jadwal->mapel->nama_mapel;
            if (!in_array($mapel, $mapelNames)) {
                $mapelNames[] = $mapel;
            }

            foreach ($sesi->absensis as $absen) {
                $nama = $absen->siswa->nama_lengkap;
                $absensiData[$nama][$mapel] = ucfirst($absen->status ?? '-');
                if (!in_array($nama, $siswaNames)) {
                    $siswaNames[] = $nama;
                }
            }
        }

        sort($mapelNames);
        sort($siswaNames);
        $this->mapelList = $mapelNames;

        // Header dua tingkat
        $header1 = ['No', 'Nama Siswa', 'Mapel'];
        $header2 = ['No', 'Nama Siswa'];
        foreach ($mapelNames as $mapel) {
            $header2[] = $mapel;
        }

        // Data isi
        $rows = [];
        foreach ($siswaNames as $index => $siswa) {
            $row = [$index + 1, $siswa];
            foreach ($mapelNames as $mapel) {
                $row[] = $absensiData[$siswa][$mapel] ?? '-';
            }
            $rows[] = $row;
        }

        return [$header1, $header2, ...$rows];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Tambah baris header keterangan
                $sheet->insertNewRowBefore(1, 2);

                $totalCols = 2 + count($this->mapelList); // No + Nama Siswa + mapel

                $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalCols);

                // Baris keterangan
                $sheet->setCellValue('A1', 'Absensi Kelas: ' . $this->kelasNama);
                $sheet->mergeCells("A1:{$lastColumn}1");

                $sheet->setCellValue('A2', 'Hari/Tanggal: ' . $this->hariTanggal);
                $sheet->mergeCells("A2:{$lastColumn}2");

                // Merge Header Baris 3-4
                $sheet->mergeCells('A3:A4'); // No
                $sheet->mergeCells('B3:B4'); // Nama Siswa
                $sheet->mergeCells("C3:{$lastColumn}3"); // Mapel

                // Styling
                $rowCount = count($this->array()) + 2;
                $sheet->getStyle("A1:{$lastColumn}{$rowCount}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                ]);

                // Bold dan warna header
                $sheet->getStyle("A1:{$lastColumn}4")->getFont()->setBold(true);
                $sheet->getStyle("A1:A2")->getFont()->setSize(13);

                $sheet->getStyle("A3:{$lastColumn}4")->applyFromArray([
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['argb' => 'FFDCE6F1'],
                    ],
                ]);
            },
        ];
    }
}
