<?php

namespace App\Exports;

use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapAbsensiWalikelasExport implements FromView, WithStyles, WithColumnWidths
{
    protected $rekapData;
    protected $dates;
    protected $kelas;
    protected $mapel;

    /**
     * @param array $rekapData Data rekapitulasi siswa.
     * @param array $dates     Array tanggal untuk header.
     * @param Kelas $kelas     Model Kelas yang difilter.
     * @param Mapel $mapel     Model Mapel yang difilter.
     */
    public function __construct(array $rekapData, array $dates, Kelas $kelas, Mapel $mapel)
    {
        $this->rekapData = $rekapData;
        $this->dates = $dates;
        $this->kelas = $kelas;
        $this->mapel = $mapel;
    }

    /**
     * Mengembalikan view yang akan dirender menjadi Excel.
     */
    public function view(): View
    {
        return view('walikelas.rekap.export', [
            'rekapData' => $this->rekapData,
            'dates'     => $this->dates,
            'kelas'     => $this->kelas,
            'mapel'     => $this->mapel,
        ]);
    }

    /**
     * Menentukan lebar kolom.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 35,  // Nama Siswa
            'C' => 15,  // NIS
        ];
    }

    /**
     * Menerapkan style pada worksheet.
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header (Baris 1-5)
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $sheet->getStyle('A3:A5')->getFont()->setBold(true);
        $sheet->getStyle('A7:Z7')->getFont()->setBold(true); // Header tabel

        // Border untuk seluruh tabel data
        $lastRow = count($this->rekapData) + 7; // 7 adalah baris awal tabel
        $lastColumn = $sheet->getHighestColumn();
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle('A7:' . $lastColumn . $lastRow)->applyFromArray($styleArray);
        $sheet->getStyle('A7:' . $lastColumn . $lastRow)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A7:Z7')->getAlignment()->setHorizontal('center');
    }
}
