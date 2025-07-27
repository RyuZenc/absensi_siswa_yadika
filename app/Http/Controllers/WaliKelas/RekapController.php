<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa; // Pastikan model Siswa di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapAbsensiWalikelasExport;

class RekapController extends Controller
{
    /**
     * Menampilkan halaman rekapitulasi absensi dengan filter.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user->guru) {
            abort(403, 'Akses hanya untuk guru wali kelas');
        }
        $guru = $user->guru;

        $kelasList = Kelas::where('guru_id', $guru->id)->get();
        $mapels = Mapel::all();
        $inputs = $request->only(['kelas_id', 'mapel_id', 'range', 'start_date', 'end_date']);

        $rekapData = [];
        $dates = [];

        if ($request->has('filter') && !empty($inputs['kelas_id']) && !empty($inputs['mapel_id'])) {
            // Panggil method private untuk mendapatkan data
            list($rekapData, $dates) = $this->getRekapData($inputs);
        }

        return view('walikelas.rekap.index', compact('kelasList', 'mapels', 'inputs', 'rekapData', 'dates'));
    }

    /**
     * Mengekspor data rekapitulasi ke file Excel.
     */
    public function export(Request $request)
    {
        $inputs = $request->only(['kelas_id', 'mapel_id', 'range', 'start_date', 'end_date']);

        if (empty($inputs['kelas_id']) || empty($inputs['mapel_id'])) {
            return redirect()->back()->with('error', 'Filter kelas dan mata pelajaran wajib diisi.');
        }

        // Panggil method private untuk mendapatkan data yang akan diekspor
        list($rekapData, $dates) = $this->getRekapData($inputs);

        // Ambil info tambahan untuk header Excel
        $kelas = Kelas::find($inputs['kelas_id']);
        $mapel = Mapel::find($inputs['mapel_id']);

        // Download file Excel
        return Excel::download(new RekapAbsensiWalikelasExport($rekapData, $dates, $kelas, $mapel), 'rekap_absensi_walikelas.xlsx');
    }

    /**
     * Method private untuk mengambil dan memproses data rekapitulasi.
     * Mencegah duplikasi kode antara index() dan export().
     *
     * @param array $inputs Filter dari request
     * @return array [rekapData, dates]
     */
    private function getRekapData(array $inputs): array
    {
        // 1. Tentukan rentang tanggal
        $start = Carbon::now()->startOfWeek();
        $end = Carbon::now()->endOfWeek();

        if (($inputs['range'] ?? 'this_week') == 'this_month') {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        } elseif ($inputs['range'] == 'custom' && !empty($inputs['start_date']) && !empty($inputs['end_date'])) {
            $start = Carbon::parse($inputs['start_date']);
            $end = Carbon::parse($inputs['end_date']);
        }

        // 2. Buat array tanggal untuk header tabel
        $dates = [];
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dates[] = $date->format('d M');
        }

        // 3. Ambil semua siswa di kelas tersebut
        $siswas = Siswa::where('kelas_id', $inputs['kelas_id'])->orderBy('nama_lengkap')->get();

        // 4. Ambil data absensi yang relevan dalam satu query
        $absensiRecords = Absensi::with('siswa')
            ->whereHas('sesiAbsen.jadwal', function ($q) use ($inputs) {
                $q->where('kelas_id', $inputs['kelas_id'])
                    ->where('mapel_id', $inputs['mapel_id']);
            })
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->keyBy(function ($item) {
                // Buat key unik: "siswa_id-tanggal" untuk akses cepat
                return $item->siswa_id . '-' . Carbon::parse($item->tanggal)->format('d M');
            });

        // 5. Susun data rekapitulasi
        $rekapData = [];
        foreach ($siswas as $siswa) {
            $absensiSiswa = [];
            foreach ($dates as $tgl) {
                $key = $siswa->id . '-' . $tgl;
                // Cek apakah ada record absensi untuk siswa pada tanggal ini
                $absensiSiswa[$tgl] = isset($absensiRecords[$key]) ? ucfirst($absensiRecords[$key]->status) : '-';
            }

            $rekapData[] = [
                'nama_lengkap' => $siswa->nama_lengkap,
                'nis' => $siswa->nis,
                'absensi' => $absensiSiswa,
            ];
        }

        return [$rekapData, $dates];
    }
}
