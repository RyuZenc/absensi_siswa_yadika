<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AbsensiHarianExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $sesiAbsenId;

    public function __construct($sesiAbsenId)
    {
        $this->sesiAbsenId = $sesiAbsenId;
    }

    public function collection()
    {
        $absensi = Absensi::with('siswa')
            ->where('sesi_absen_id', $this->sesiAbsenId)
            ->get()
            ->sortBy(function ($absen) {
                return $absen->siswa->nama_lengkap;
            })
            ->values(); // reset index

        $no = 1;
        return $absensi->map(function ($absen) use (&$no) {
            return [
                'No'     => $no++,
                'Nama'   => $absen->siswa->nama_lengkap,
                'Status' => ucfirst($absen->status),
                'Waktu'  => $absen->created_at->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s')
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Nama Siswa', 'Status', 'Waktu Absensi'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $rowCount = $this->collection()->count() + 1; // +1 untuk heading
                $columnCount = 4;

                $cellRange = 'A1:' . $sheet->getCellByColumnAndRow($columnCount, $rowCount)->getCoordinate();

                // Tambahkan border
                $sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                // Warna latar belakang heading
                $sheet->getStyle('A1:D1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFDCE6F1'],
                    ],
                ]);
            },
        ];
    }
}
