<?php

namespace App\Http\Controllers\Guru;

use App\Exports\RekapAbsensiGuruExport;
use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\SesiAbsen;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $guru = Auth::user()->guru;

        $jadwals = Jadwal::where('guru_id', $guru->id)->with(['mapel', 'kelas'])->get();

        $mapels = $jadwals->map->mapel->unique('id')->sortBy('nama_mapel');
        $kelasList = $jadwals->map->kelas->unique('id')->sortBy('nama_kelas');

        $rekapData = [];
        $dates = [];
        $summary = [];
        $filterInfo = [];
        $selectedKelas = null;

        if ($request->has('filter')) {
            $validated = $this->validateRequest($request);
            list($startDate, $endDate) = $this->resolveDateRange($validated['range'], $request);

            if ($startDate && $endDate) {
                $result = $this->getRekapData($guru->id, $validated['kelas_id'], $validated['mapel_id'], $startDate, $endDate);
                $rekapData = $result['students'];
                $dates = $result['dates'];
                $summary = $result['summary'];
                $selectedKelas = $kelasList->firstWhere('id', $validated['kelas_id']);

                $filterInfo = [
                    'mapel' => $mapels->find($validated['mapel_id'])->nama_mapel,
                    'kelas' => $selectedKelas,
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

    public function export(Request $request)
    {
        $validated = $this->validateRequest($request);
        $guru = Auth::user()->guru;
        list($startDate, $endDate) = $this->resolveDateRange($validated['range'], $request);

        $result = $this->getRekapData($guru->id, $validated['kelas_id'], $validated['mapel_id'], $startDate, $endDate);

        $jadwal = Jadwal::where('guru_id', $guru->id)
            ->where('kelas_id', $validated['kelas_id'])
            ->where('mapel_id', $validated['mapel_id'])
            ->with(['mapel', 'kelas'])
            ->firstOrFail();

        $filterInfo = [
            'mapel' => $jadwal->mapel->nama_mapel,
            'guru' => $guru->nama_lengkap,
            'periode' => $startDate->isoFormat('D MMMM Y') . ' - ' . $endDate->isoFormat('D MMMM Y'),
        ];

        $filename = 'rekap-absensi-' . str_replace(' ', '_', $jadwal->kelas->nama_kelas) . '-' . now()->timestamp . '.xlsx';

        return Excel::download(new RekapAbsensiGuruExport($result, $filterInfo, $jadwal->kelas), $filename);
    }

    private function getRekapData($guruId, $kelasId, $mapelId, $startDate, $endDate)
    {
        $sesiAbsens = SesiAbsen::whereHas('jadwal', function ($query) use ($guruId, $mapelId, $kelasId) {
            $query->where('guru_id', $guruId)
                ->where('mapel_id', $mapelId)
                ->where('kelas_id', $kelasId);
        })
            ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->with('absensis.siswa')
            ->orderBy('tanggal', 'asc')
            ->get();

        if ($sesiAbsens->isEmpty()) {
            return ['dates' => [], 'students' => [], 'summary' => []];
        }

        $dates = $sesiAbsens->pluck('tanggal')->unique()->map(fn($date) => Carbon::parse($date)->format('d/m'))->sort()->values();

        $siswas = Siswa::where('kelas_id', $kelasId)->orderBy('nama_lengkap', 'asc')->get();
        $absensiMap = $this->buildAbsensiMap($sesiAbsens);

        $rekapData = $this->buildRekapData($siswas, $dates, $absensiMap);
        $summary = $this->calculateSummary($rekapData, $dates);

        return ['dates' => $dates, 'students' => $rekapData, 'summary' => $summary];
    }

    private function validateRequest(Request $request)
    {
        return $request->validate([
            'mapel_id' => 'required|exists:mapels,id',
            'kelas_id' => 'required|exists:kelas,id',
            'range' => 'required|string',
            'start_date' => 'nullable|date|required_if:range,custom',
            'end_date' => 'nullable|date|after_or_equal:start_date|required_if:range,custom',
        ]);
    }

    private function resolveDateRange($range, Request $request)
    {
        return match ($range) {
            'this_week' => [now()->startOfWeek(), now()->endOfWeek()],
            'this_month' => [now()->startOfMonth(), now()->endOfMonth()],
            'custom' => [Carbon::parse($request->start_date), Carbon::parse($request->end_date)],
            default => [now()->startOfWeek(), now()->endOfWeek()],
        };
    }

    private function buildAbsensiMap($sesiAbsens)
    {
        $map = [];
        foreach ($sesiAbsens as $sesi) {
            $tanggalFormatted = Carbon::parse($sesi->tanggal)->format('d/m');
            foreach ($sesi->absensis as $absen) {
                if ($absen->siswa_id) {
                    $map[$absen->siswa_id][$tanggalFormatted] = ucfirst($absen->status);
                }
            }
        }
        return $map;
    }

    private function buildRekapData($siswas, $dates, $absensiMap)
    {
        $rekap = [];
        foreach ($siswas as $siswa) {
            $absensiSiswa = [];
            foreach ($dates as $date) {
                $absensiSiswa[$date] = $absensiMap[$siswa->id][$date] ?? '-';
            }
            $rekap[] = [
                'nis' => $siswa->nis,
                'nama_lengkap' => $siswa->nama_lengkap,
                'absensi' => $absensiSiswa,
            ];
        }
        return $rekap;
    }

    private function calculateSummary($rekapData, $dates)
    {
        $summary = [];
        foreach ($dates as $date) {
            $dailyTotals = ['Hadir' => 0, 'Sakit' => 0, 'Izin' => 0, 'Alfa' => 0, '-' => 0];
            foreach ($rekapData as $student) {
                $status = $student['absensi'][$date] ?? '-';
                $statusKey = $status === 'Alpha' ? 'Alfa' : $status;
                if (array_key_exists($statusKey, $dailyTotals)) {
                    $dailyTotals[$statusKey]++;
                }
            }
            $summary[$date] = $dailyTotals;
        }
        return $summary;
    }
}
