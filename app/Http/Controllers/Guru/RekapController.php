<?php

namespace App\Http\Controllers\Guru;

use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\SesiAbsen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapAbsensiGuruExport;

class RekapController extends Controller
{
    /**
     * Menampilkan halaman rekap absensi dengan filter.
     */
    public function rekap(Request $request)
    {
        $guru = Auth::user()->guru;
        $guruId = $guru->id;

        // Ambil jadwal guru beserta mapel dan kelas
        $jadwals = Jadwal::where('guru_id', $guruId)->with(['mapel', 'kelas'])->get();

        // Ambil mapel dan kelas unik yang diampu guru
        $mapels = $jadwals->map->mapel->unique('id')->sortBy('nama_mapel');
        $kelasList = $jadwals->map->kelas->unique('id')->sortBy('nama_kelas');

        $rekapData = [];
        $dates = [];
        $summary = [];
        $filterInfo = [];

        if ($request->has('filter')) {
            $request->validate([
                'mapel_id' => 'required|exists:mapels,id',
                'kelas_id' => 'required|exists:kelas,id',
                'range' => 'required|string',
                'start_date' => 'nullable|date|required_if:range,custom',
                'end_date' => 'nullable|date|after_or_equal:start_date|required_if:range,custom',
            ]);

            $selectedMapelId = $request->input('mapel_id');
            $selectedKelasId = $request->input('kelas_id');
            $range = $request->input('range');

            $startDate = null;
            $endDate = null;
            switch ($range) {
                case 'this_week':
                    $startDate = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now()->endOfWeek();
                    break;
                case 'this_month':
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    break;
                case 'custom':
                    $startDate = Carbon::parse($request->input('start_date'));
                    $endDate = Carbon::parse($request->input('end_date'));
                    break;
            }

            if ($startDate && $endDate) {
                $result = $this->getRekapData($guruId, $selectedKelasId, $selectedMapelId, $startDate, $endDate);
                $rekapData = $result['students'];
                $dates = $result['dates'];
                $summary = $result['summary'];

                $mapelInfo = $mapels->find($selectedMapelId);
                $kelasInfo = $kelasList->firstWhere('id', $selectedKelasId);

                $filterInfo = [
                    'mapel' => $mapelInfo->nama_mapel,
                    'kelas' => $kelasInfo->nama_kelas,
                    'guru' => $guru->nama_lengkap,
                    'periode' => $startDate->isoFormat('D MMMM Y') . ' - ' . $endDate->isoFormat('D MMMM Y'),
                ];
            }
        }

        return view('guru.rekap.index', [
            'mapels' => $mapels,
            'kelasList' => $kelasList,
            'rekapData' => $rekapData,
            'dates' => $dates,
            'summary' => $summary,
            'filterInfo' => $filterInfo,
            'inputs' => $request->all(),
        ]);
    }

    /**
     * Mengekspor data rekap absensi ke file Excel.
     */
    public function exportRekap(Request $request)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mapels,id',
            'kelas_id' => 'required|exists:kelas,id',
            'range' => 'required|string',
            'start_date' => 'nullable|date|required_if:range,custom',
            'end_date' => 'nullable|date|after_or_equal:start_date|required_if:range,custom',
        ]);

        $guru = Auth::user()->guru;
        $range = $request->input('range');

        $startDate = match ($range) {
            'this_week' => now()->startOfWeek(),
            'this_month' => now()->startOfMonth(),
            'custom' => Carbon::parse($request->start_date),
        };

        $endDate = match ($range) {
            'this_week' => now()->endOfWeek(),
            'this_month' => now()->endOfMonth(),
            'custom' => Carbon::parse($request->end_date),
        };

        $result = $this->getRekapData($guru->id, $request->kelas_id, $request->mapel_id, $startDate, $endDate);

        $jadwal = Jadwal::where([
            'guru_id' => $guru->id,
            'kelas_id' => $request->kelas_id,
            'mapel_id' => $request->mapel_id,
        ])->with(['mapel', 'kelas'])->firstOrFail();

        $filterInfo = [
            'mapel' => $jadwal->mapel->nama_mapel,
            'kelas' => $jadwal->kelas->nama_kelas,
            'guru' => $guru->nama_lengkap,
            'periode' => $startDate->isoFormat('D MMMM Y') . ' - ' . $endDate->isoFormat('D MMMM Y'),
        ];

        $filename = 'rekap-absensi-harian-' . str_replace(' ', '_', $filterInfo['kelas']) . '-' . now()->timestamp . '.xlsx';
        $kelas = $jadwal->kelas;

        return Excel::download(new RekapAbsensiGuruExport($result, $filterInfo, $kelas), $filename);
    }

    /**
     * Helper method untuk mengambil dan memproses data rekapitulasi absensi.
     */
    private function getRekapData($guruId, $kelasId, $mapelId, $startDate, $endDate)
    {
        $sesiAbsens = SesiAbsen::whereHas('jadwal', function ($query) use ($guruId, $mapelId, $kelasId) {
            $query->where('guru_id', $guruId)
                ->where('mapel_id', $mapelId)
                ->where('kelas_id', $kelasId);
        })
            ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->with('absensis')
            ->orderBy('tanggal', 'asc')
            ->get();

        if ($sesiAbsens->isEmpty()) {
            return [
                'dates' => [],
                'students' => [],
                'summary' => [],
            ];
        }

        $dates = $sesiAbsens->pluck('tanggal')->unique()->map(function ($date) {
            return Carbon::parse($date)->format('d/m/Y');
        })->sort()->values();

        $siswas = Siswa::where('kelas_id', $kelasId)->orderBy('nama_lengkap', 'asc')->get();

        $absensiMap = [];
        foreach ($sesiAbsens as $sesi) {
            $tanggalFormatted = Carbon::parse($sesi->tanggal)->format('d/m/Y');
            foreach ($sesi->absensis as $absen) {
                if ($absen->siswa_id) {
                    $absensiMap[$absen->siswa_id][$tanggalFormatted] = $absen->status;
                }
            }
        }

        $rekapData = [];
        foreach ($siswas as $siswa) {
            $absensiSiswa = [];
            foreach ($dates as $date) {
                $status = $absensiMap[$siswa->id][$date] ?? '-';

                switch (strtolower($status)) {
                    case 'hadir':
                        $absensiSiswa[$date] = 'Hadir';
                        break;
                    case 'sakit':
                        $absensiSiswa[$date] = 'Sakit';
                        break;
                    case 'izin':
                        $absensiSiswa[$date] = 'Izin';
                        break;
                    case 'alpha':
                        $absensiSiswa[$date] = 'Alfa';
                        break;
                    default:
                        $absensiSiswa[$date] = '-';
                        break;
                }
            }
            $rekapData[] = [
                'nis' => $siswa->nis,
                'nama_lengkap' => $siswa->nama_lengkap,
                'absensi' => $absensiSiswa,
            ];
        }

        $summary = [];
        foreach ($dates as $date) {
            $dailyTotals = ['Hadir' => 0, 'Sakit' => 0, 'Izin' => 0, 'Alfa' => 0];
            foreach ($rekapData as $student) {
                $status = $student['absensi'][$date];
                if (array_key_exists($status, $dailyTotals)) {
                    $dailyTotals[$status]++;
                }
            }
            $summary[$date] = $dailyTotals;
        }

        return [
            'dates' => $dates,
            'students' => $rekapData,
            'summary' => $summary,
        ];
    }
}
