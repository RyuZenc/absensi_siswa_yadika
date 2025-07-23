<?php

namespace App\Exports;

use App\Models\SesiAbsen;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AbsensiExport implements FromView, ShouldAutoSize
{
    protected $sesiAbsen;

    public function __construct(SesiAbsen $sesiAbsen)
    {
        $this->sesiAbsen = $sesiAbsen;
    }

    public function view(): View
    {
        $dataAbsensi = [];

        foreach ($this->sesiAbsen->jadwal->kelas->siswas as $siswa) {
            $dataAbsensi[$siswa->id] = [
                'nama_siswa' => $siswa->nama_lengkap,
                'status' => 'Alpha',
            ];
        }

        foreach ($this->sesiAbsen->absensis as $absensi) {
            if (isset($dataAbsensi[$absensi->siswa_id])) {
                $dataAbsensi[$absensi->siswa_id]['status'] = ucfirst($absensi->status);
            }
        }

        usort($dataAbsensi, function ($a, $b) {
            return strcmp($a['nama_siswa'], $b['nama_siswa']);
        });


        return view('exports.absensi', [
            'sesiAbsen' => $this->sesiAbsen,
            'absensis' => $dataAbsensi,
        ]);
    }
}
