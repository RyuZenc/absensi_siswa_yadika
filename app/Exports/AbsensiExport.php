<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AbsensiExport implements FromView
{
    protected $sesi;

    public function __construct($sesi)
    {
        $this->sesi = $sesi;
    }

    public function view(): View
    {
        $absensis = $this->sesi->absensis()->with('siswa')->get();

        return view('exports.absensi', [
            'absensis' => $absensis,
            'jadwal' => $this->sesi->jadwal,
            'tanggal' => $this->sesi->tanggal,
        ]);
    }
}
