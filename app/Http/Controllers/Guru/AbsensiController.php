<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\SesiAbsen;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiHarianExport;

class AbsensiController extends Controller
{
    // Menampilkan jadwal guru hari ini
    public function dashboard()
    {
        $hariIni = Carbon::now()->isoFormat('dddd');
        $guru = Auth::user()->guru;
        $jadwals = Jadwal::where('guru_id', $guru->id)
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        return view('guru.dashboard', compact('jadwals'));
    }

    public function daftarKelas()
    {
        $guruId = Auth::user()->guru->id;

        $jadwals = Jadwal::with(['kelas', 'mapel'])
            ->where('guru_id', $guruId)
            ->orderBy('kelas_id')
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->get()
            ->groupBy('kelas_id');

        return view('guru.kelas.index', compact('jadwals'));
    }

    public function show(Jadwal $jadwal)
    {
        $tanggalHariIni = Carbon::today()->toDateString();
        $sesiAbsen = SesiAbsen::firstOrCreate(
            [
                'jadwal_id' => $jadwal->id,
                'tanggal' => $tanggalHariIni,
            ],
            [
                'guru_id' => $jadwal->guru_id,
                'berlaku_hingga' => Carbon::parse($tanggalHariIni . ' ' . $jadwal->jam_selesai),
            ]
        );

        $siswas = $jadwal->kelas->siswas()->orderBy('nama_lengkap')->get();
        $absensiSudahAda = Absensi::where('sesi_absen_id', $sesiAbsen->id)
            ->pluck('status', 'siswa_id');

        $absensiCounts = [
            'hadir' => 0,
            'sakit' => 0,
            'izin' => 0,
            'alpha' => 0,
        ];

        foreach ($absensiSudahAda as $status) {
            if (isset($absensiCounts[$status])) {
                $absensiCounts[$status]++;
            }
        }

        return view('guru.absensi.show', compact(
            'jadwal',
            'sesiAbsen',
            'siswas',
            'absensiSudahAda',
            'absensiCounts'
        ));

        return view('guru.absensi.show', compact('jadwal', 'sesiAbsen', 'siswas', 'absensiSudahAda'));
    }

    public function createCode(Request $request, SesiAbsen $sesiAbsen)
    {
        $request->validate([
            'durasi' => 'required|integer|min:1|max:60',
        ]);

        $batasWaktuMaksimum = Carbon::parse($sesiAbsen->tanggal . ' ' . $sesiAbsen->jadwal->jam_selesai)->addMinutes(60);
        if (now()->greaterThan($batasWaktuMaksimum)) {
            return back()->with('error', 'Anda sudah terlalu lama dari jadwal. Tidak disarankan membuat kode absen.');
        }

        $durasiMenit = (int) $request->input('durasi');
        $waktuBerlaku = now()->addMinutes($durasiMenit);

        $sesiAbsen->update([
            'kode_absen' => Str::upper(Str::random(6)),
            'berlaku_hingga' => $waktuBerlaku,
        ]);

        return back()->with('success', "Kode absensi berhasil dibuat dan berlaku hingga {$waktuBerlaku->format('H:i')}.");
    }

    public function storeManual(Request $request, SesiAbsen $sesiAbsen)
    {
        $request->validate([
            'absensi' => 'required|array',
            'absensi.*' => 'in:hadir,sakit,izin,alpha'
        ]);

        foreach ($request->absensi as $siswaId => $status) {
            Absensi::updateOrCreate(
                [
                    'sesi_absen_id' => $sesiAbsen->id,
                    'siswa_id' => $siswaId,
                    'tanggal' => $sesiAbsen->tanggal,
                ],
                [
                    'status' => $status,
                ]
            );
        }

        return redirect()->back()->with('success', 'Absensi berhasil disimpan.');
    }

    public function export($sesiAbsenId)
    {
        return Excel::download(new AbsensiHarianExport($sesiAbsenId), 'absensi_harian.xlsx');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'sesi_absen_id' => 'required|exists:sesi_absens,id',
            'status' => 'required|in:hadir,sakit,izin,alpha',
        ]);

        $tanggal = SesiAbsen::where('id', $request->sesi_absen_id)->value('tanggal');
        $absensi = Absensi::updateOrCreate(
            [
                'sesi_absen_id' => $request->sesi_absen_id,
                'siswa_id' => $request->siswa_id
            ],
            [
                'status' => $request->status,
                'tanggal' => $tanggal,
            ]
        );

        return response()->json(['success' => true, 'data' => $absensi]);
    }

    public function riwayat()
    {
        $guru = Auth::user()->guru;

        $sesiAbsens = SesiAbsen::with(['jadwal.mapel', 'jadwal.kelas'])
            ->whereHas('jadwal', fn($q) => $q->where('guru_id', $guru->id))
            ->orderByDesc('tanggal')
            ->get();

        return view('guru.riwayat.riwayat', compact('sesiAbsens'));
    }

    public function detail($id)
    {
        $sesi = SesiAbsen::with(['jadwal.mapel', 'jadwal.kelas', 'absensis.siswa'])
            ->findOrFail($id);

        $absensiCounts = [
            'hadir' => 0,
            'sakit' => 0,
            'izin' => 0,
            'alpha' => 0,
        ];

        foreach ($sesi->absensis as $absen) {
            if (isset($absensiCounts[$absen->status])) {
                $absensiCounts[$absen->status]++;
            }
        }

        return view('guru.riwayat.detail', compact('sesi', 'absensiCounts'));
    }
}
