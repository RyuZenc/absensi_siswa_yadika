<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RekapAbsensiGuruExport implements FromView, WithTitle, ShouldAutoSize
{
    public function __construct(
        public array $data,
        public array $filterInfo,
        public $kelas,
    ) {}

    public function view(): View
    {
        return view('guru.rekap.export', [
            'dates' => $this->data['dates'],
            'students' => $this->data['students'],
            'filterInfo' => $this->filterInfo,
            'kelas' => $this->kelas,
        ]);
    }

    public function title(): string
    {
        return 'Rekap Harian ' . $this->filterInfo['kelas'];
    }
}
