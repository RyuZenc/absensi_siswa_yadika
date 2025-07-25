<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SesiAbsen;
use App\Models\Absensi;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanAbsensiExport;

class LaporanAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Kelas::orderByRaw("FIELD(tingkat, 'X', 'XI', 'XII')")
            ->orderBy('nama_kelas')
            ->get();

        $kelasId = $request->kelas_id;
        $tanggal = $request->tanggal;

        $sesiList = collect();

        if ($kelasId && $tanggal) {
            $sesiList = SesiAbsen::with(['jadwal.kelas', 'absensis.siswa'])
                ->whereDate('tanggal', $tanggal)
                ->whereHas('jadwal', function ($query) use ($kelasId) {
                    $query->where('kelas_id', $kelasId);
                })
                ->get();
        }

        return view('admin.laporan.absensi', compact('kelasList', 'sesiList'));
    }

    public function export(Request $request)
    {
        return Excel::download(new LaporanAbsensiExport($request->kelas_id, $request->tanggal), 'laporan_absensi.xlsx');
    }
}
