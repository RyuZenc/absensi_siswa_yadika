<?php

namespace App\Exports;

use App\Models\SesiAbsen;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanHarianWalikelas implements FromCollection, WithHeadings, WithEvents, WithCustomStartCell, ShouldAutoSize
{
    protected $kelasId;
    protected $tanggal;

    protected $mapelList;
    protected $siswaList;
    protected $absensiMap;

    protected $totalKehadiran;

    protected $collectionData;

    public function __construct($kelasId, $tanggal)
    {
        $this->kelasId = $kelasId;
        $this->tanggal = $tanggal;

        // Ambil sesi absensi yang sesuai kelas dan tanggal
        $sesiList = SesiAbsen::with(['absensis.siswa', 'jadwal.mapel', 'jadwal.kelas', 'jadwal.guru'])
            ->whereHas('jadwal', function ($query) {
                $query->where('kelas_id', $this->kelasId);
            })
            ->whereDate('tanggal', $this->tanggal)
            ->get();

        // Ambil daftar mapel unik urut alfabet
        $this->mapelList = $sesiList->pluck('jadwal.mapel.nama_mapel')->unique()->sort()->values();

        $this->siswaList = collect();
        $this->absensiMap = [];
        $this->totalKehadiran = [];

        foreach ($sesiList as $sesi) {
            $mapel = $sesi->jadwal->mapel->nama_mapel;

            foreach ($sesi->absensis as $absen) {
                $nama = $absen->siswa->nama_lengkap;
                $status = ucfirst(strtolower($absen->status ?? '-'));

                $this->absensiMap[$nama][$mapel] = $status;
                $this->siswaList->put($nama, $absen->siswa);

                // Inisialisasi jika belum ada data total
                if (!isset($this->totalKehadiran[$nama])) {
                    $this->totalKehadiran[$nama] = [
                        'Hadir' => 0,
                        'Sakit' => 0,
                        'Izin' => 0,
                        'Alpha' => 0,
                    ];
                }

                // Hitung total berdasar status
                if (in_array($status, ['Hadir', 'Sakit', 'Izin', 'Alpha'])) {
                    $this->totalKehadiran[$nama][$status]++;
                }
            }
        }

        // Urutkan siswa berdasarkan nama
        $this->siswaList = $this->siswaList->sortKeys();
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function collection()
    {
        $rows = collect();
        $index = 1;

        foreach ($this->siswaList as $nama => $siswa) {
            $row = [$index++, $nama];

            // Data status tiap mapel
            foreach ($this->mapelList as $mapel) {
                $row[] = $this->absensiMap[$nama][$mapel] ?? '-';
            }

            // Data total kehadiran
            $totals = $this->totalKehadiran[$nama] ?? ['Hadir' => 0, 'Sakit' => 0, 'Izin' => 0, 'Alpha' => 0];
            $row[] = $totals['Hadir'];
            $row[] = $totals['Sakit'];
            $row[] = $totals['Izin'];
            $row[] = $totals['Alpha'];

            $rows->push($row);
        }

        $this->collectionData = $rows;

        return $rows;
    }

    public function headings(): array
    {
        $head1 = ['No', 'Nama Siswa'];
        $head2 = ['', ''];

        // Header mapel (gabungkan dengan judul "Mapel" di baris atas)
        foreach ($this->mapelList as $mapel) {
            $head1[] = 'Mapel';
            $head2[] = $mapel;
        }

        // Header untuk total kehadiran (4 kolom)
        $head1 = array_merge($head1, ['Total Kehadiran', '', '', '']);
        $head2 = array_merge($head2, ['Hadir', 'Sakit', 'Izin', 'Alpha']);

        return [$head1, $head2];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                /** @var Worksheet $sheet */
                $sheet = $event->sheet->getDelegate();

                \Carbon\Carbon::setLocale('id');
                $tanggal = \Carbon\Carbon::parse($this->tanggal)->translatedFormat('l, d/m/Y');

                $jumlahKolom = 2 + $this->mapelList->count() + 4; // 2 kolom awal + mapel + 4 total kehadiran
                $lastCol = Coordinate::stringFromColumnIndex($jumlahKolom);

                // Judul laporan dan tanggal (merge seluruh kolom)
                $sheet->mergeCells("A1:{$lastCol}1");
                $sheet->setCellValue("A1", 'Laporan Absensi Harian - Wali Kelas');

                $sheet->mergeCells("A2:{$lastCol}2");
                $sheet->setCellValue("A2", 'Tanggal: ' . $tanggal);

                // Merge header "No" dan "Nama Siswa" baris 5-6
                $sheet->mergeCells("A5:A6");
                $sheet->mergeCells("B5:B6");

                // Merge header "Mapel" dari kolom 3 sampai kolom sebelum total kehadiran
                $mapelStart = Coordinate::stringFromColumnIndex(3);
                $mapelEnd = Coordinate::stringFromColumnIndex(2 + $this->mapelList->count());
                $sheet->mergeCells("{$mapelStart}5:{$mapelEnd}5");

                // Merge header "Total Kehadiran" (4 kolom) di baris 5
                $totalMulai = Coordinate::stringFromColumnIndex(3 + $this->mapelList->count());
                $totalAkhir = Coordinate::stringFromColumnIndex($jumlahKolom);
                $sheet->mergeCells("{$totalMulai}5:{$totalAkhir}5");

                // Styling judul dan tanggal
                $sheet->getStyle("A1:{$lastCol}2")->applyFromArray([
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
                        ],
                    ],
                ]);

                // Styling header tabel (baris 5-6)
                $sheet->getStyle("A5:{$lastCol}6")->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFC2D9F1'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Styling isi tabel
                $startRow = 7;
                $endRow = $startRow + $this->collectionData->count() - 1;
                $sheet->getStyle("A{$startRow}:{$lastCol}{$endRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // AutoSize semua kolom
                for ($i = 1; $i <= $jumlahKolom; $i++) {
                    $colLetter = Coordinate::stringFromColumnIndex($i);
                    $sheet->getColumnDimension($colLetter)->setAutoSize(true);
                }
            },
        ];
    }
}
