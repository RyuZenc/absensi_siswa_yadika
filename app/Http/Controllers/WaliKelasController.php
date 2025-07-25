<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\SesiAbsen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanHarianWalikelas;

class WaliKelasController extends Controller
{
    public function cekKelas(Request $request)
    {
        $waliKelas = Auth::user()->guru;
        $kelasDiampu = $waliKelas->kelasYangDiampu;

        if (!$kelasDiampu) {
            return view('walikelas.cek_kelas', [
                'kelasList' => collect(),
                'sesiList' => collect(),
                'tanggalDipilih' => null,
            ])->with('error', 'Anda belum diampu untuk kelas manapun.');
        }

        $kelasList = collect([$kelasDiampu]);
        $tanggalDipilih = $request->input('tanggal', date('Y-m-d'));

        $sesiList = SesiAbsen::with(['absensis.siswa', 'jadwal.mapel'])
            ->whereHas('jadwal', function ($query) use ($kelasDiampu) {
                $query->where('kelas_id', $kelasDiampu->id);
            })
            ->whereDate('tanggal', $tanggalDipilih)
            ->get();

        return view('walikelas.cek_kelas', compact('kelasList', 'sesiList', 'tanggalDipilih'));
    }

    public function export(Request $request)
    {
        $waliKelas = Auth::user()->guru;
        $kelasDiampu = $waliKelas->kelasYangDiampu;

        if (!$kelasDiampu) {
            return redirect()->back()->with('error', 'Anda tidak mengampu kelas manapun.');
        }

        $tanggal = $request->tanggal;
        $kelasId = $request->kelas_id;

        if (!$tanggal || !$kelasId) {
            return redirect()->back()->with('error', 'Kelas dan tanggal diperlukan untuk ekspor.');
        }

        if ($kelasId != $kelasDiampu->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke kelas yang diminta.');
        }

        return Excel::download(
            new LaporanHarianWalikelas($kelasId, $tanggal),
            'Laporan Absensi Harian - ' . $kelasDiampu->nama_kelas . ' - ' . $tanggal . '.xlsx'
        );
    }
}
