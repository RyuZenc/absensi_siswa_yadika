<?php

namespace App\Exports;

use App\Models\SesiAbsen;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class LaporanHarianWalikelas implements FromCollection, WithHeadings, WithEvents
{
    protected $kelasId;
    protected $tanggal;

    protected $mapelList;
    protected $siswaList;
    protected $absensiMap;

    public function __construct($kelasId, $tanggal)
    {
        $this->kelasId = $kelasId;
        $this->tanggal = $tanggal;

        $sesiList = SesiAbsen::with(['absensis.siswa', 'jadwal.mapel'])
            ->whereHas('jadwal', function ($query) {
                $query->where('kelas_id', $this->kelasId);
            })
            ->whereDate('tanggal', $this->tanggal)
            ->get();

        $this->mapelList = $sesiList->pluck('jadwal.mapel.nama_mapel')->unique()->sort()->values();

        $this->siswaList = collect();
        $this->absensiMap = [];

        foreach ($sesiList as $sesi) {
            $mapel = $sesi->jadwal->mapel->nama_mapel;

            foreach ($sesi->absensis as $absen) {
                $nama = $absen->siswa->nama_lengkap;
                $this->absensiMap[$nama][$mapel] = ucfirst($absen->status ?? '-');
                $this->siswaList->put($nama, $absen->siswa);
            }
        }

        $this->siswaList = $this->siswaList->sortKeys();
    }

    public function collection()
    {
        $rows = collect();

        $index = 1;
        foreach ($this->siswaList as $nama => $siswa) {
            $row = [
                $index,
                $nama,
            ];

            foreach ($this->mapelList as $mapel) {
                $row[] = $this->absensiMap[$nama][$mapel] ?? '-';
            }

            $rows->push($row);
            $index++;
        }

        return $rows;
    }

    public function headings(): array
    {
        $headings = ['No', 'Nama Siswa'];
        foreach ($this->mapelList as $mapel) {
            $headings[] = $mapel;
        }
        return $headings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $lastColumn = $this->getLastColumn();

                // Styling header: bold font
                $sheet->getStyle("A1:{$lastColumn}1")->getFont()->setBold(true);

                // Auto size all columns from A to lastColumn
                $startIndex = Coordinate::columnIndexFromString('A');
                $endIndex = Coordinate::columnIndexFromString($lastColumn);

                for ($col = $startIndex; $col <= $endIndex; $col++) {
                    $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
                }
            }
        ];
    }

    protected function getLastColumn(): string
    {
        $countMapel = $this->mapelList->count();

        $lastColIndex = 2 + $countMapel;

        return Coordinate::stringFromColumnIndex($lastColIndex);
    }
}
