<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\SesiAbsen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiHarianGuruExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    protected $sesiAbsenId;
    protected $collectionData;

    public function __construct($sesiAbsenId)
    {
        $this->sesiAbsenId = $sesiAbsenId;
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function collection()
    {
        $absensi = Absensi::with('siswa')
            ->where('sesi_absen_id', $this->sesiAbsenId)
            ->get()
            ->sortBy(fn($absen) => $absen->siswa->nama_lengkap)
            ->values();

        $this->collectionData = $absensi;

        $no = 1;
        return $absensi->map(function ($absen) use (&$no) {
            return [
                'No'     => $no++,
                'Nama'   => $absen->siswa->nama_lengkap,
                'Status' => ucfirst($absen->status),
                'Waktu'  => $absen->created_at->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Nama Siswa', 'Status', 'Waktu Absensi'];
    }

    public function registerEvents(): array
    {
        $sesi = SesiAbsen::with('jadwal.mapel', 'jadwal.kelas', 'jadwal.guru')->find($this->sesiAbsenId);

        return [
            AfterSheet::class => function (AfterSheet $event) use ($sesi) {
                /** @var Worksheet $sheet */
                $sheet = $event->sheet->getDelegate();

                $sheet->mergeCells('A1:D1');
                $sheet->setCellValue('A1', 'Kelas: ' . $sesi->jadwal->kelas->tingkat . ' - ' . $sesi->jadwal->kelas->nama_kelas);

                $sheet->mergeCells('A2:D2');
                $sheet->setCellValue('A2', 'Mata Pelajaran: ' . $sesi->jadwal->mapel->nama_mapel);

                \Carbon\Carbon::setLocale('id');
                $hariTanggal = \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('l, d/m/Y');
                $sheet->mergeCells('A3:D3');
                $sheet->setCellValue('A3', 'Tanggal: ' . $hariTanggal);

                $sheet->mergeCells('A4:D4');
                $sheet->setCellValue('A4', 'Guru Pengajar: ' . ($sesi->jadwal->guru->nama_lengkap ?? '-'));

                $dataCount = $this->collectionData ? $this->collectionData->count() : 0;
                $startRow = 5;
                $endRow = $startRow + $dataCount;
                $columnCount = 4;
                $lastColumnLetter = Coordinate::stringFromColumnIndex($columnCount);

                $sheet->getStyle('A1:D4')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF99CC66'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                $sheet->getStyle("A{$startRow}:{$lastColumnLetter}{$startRow}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFC2D9F1'],
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                $sheet->getStyle("A{$startRow}:{$lastColumnLetter}{$endRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
