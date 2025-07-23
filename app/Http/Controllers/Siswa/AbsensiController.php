<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SesiAbsen;
use App\Models\Absensi;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function dashboard()
    {
        $siswa = Auth::user()->siswa;
        $tanggalHariIni = Carbon::today()->toDateString();
        $hariIni = Carbon::now()->isoFormat('dddd');

        // Ambil jadwal siswa hari ini
        $jadwalHariIni = Jadwal::with(['mapel', 'guru', 'kelas'])
            ->where('kelas_id', $siswa->kelas_id)
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai')
            ->get();

        $sesiAbsenHariIni = SesiAbsen::whereIn('jadwal_id', $jadwalHariIni->pluck('id'))
            ->where('tanggal', $tanggalHariIni)
            ->whereNotNull('kode_absen')
            ->first();

        // Ambil riwayat absensi terbaru siswa
        $riwayatAbsensi = Absensi::where('siswa_id', $siswa->id)
            ->with(['sesiAbsen.jadwal.mapel'])
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();


        return view('siswa.dashboard', compact('jadwalHariIni', 'sesiAbsenHariIni', 'riwayatAbsensi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_absen' => 'required|string|size:6',
        ]);

        $siswa = Auth::user()->siswa;
        $tanggalHariIni = Carbon::today()->toDateString();

        $sesiAbsen = SesiAbsen::where('kode_absen', strtoupper($request->kode_absen))
            ->where('tanggal', $tanggalHariIni)
            ->whereHas('jadwal', function ($query) use ($siswa) {
                $query->where('kelas_id', $siswa->kelas_id);
            })
            ->where('berlaku_hingga', '>', Carbon::now())
            ->first();

        if (!$sesiAbsen) {
            return back()->withErrors(['kode_absen' => 'Kode absensi tidak valid, sudah kadaluarsa, atau bukan untuk kelas Anda.'])->withInput();
        }

        $sudahAbsen = Absensi::where('sesi_absen_id', $sesiAbsen->id)
            ->where('siswa_id', $siswa->id)
            ->exists();

        if ($sudahAbsen) {
            return back()->withErrors(['kode_absen' => 'Anda sudah absen untuk sesi ini.']);
        }

        Absensi::create([
            'sesi_absen_id' => $sesiAbsen->id,
            'siswa_id' => $siswa->id,
            'tanggal' => $tanggalHariIni,
            'status' => 'hadir',
        ]);

        return back()->with('success', 'Absensi berhasil direkam!');
    }
}
