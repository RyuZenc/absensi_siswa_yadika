<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SesiAbsen;
use App\Models\Absensi;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AbsensiController extends Controller
{
    public function dashboard()
    {
        $hariIni = Carbon::now()->isoFormat('dddd');
        $siswa = Auth::user()->siswa;

        $jadwals = Jadwal::where('kelas_id', $siswa->kelas_id)
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        $riwayatAbsensi = Absensi::where('siswa_id', $siswa->id)
            ->latest()
            ->take(5)
            ->get();

        return view('siswa.dashboard', compact('jadwals', 'riwayatAbsensi'));
    }

    public function store(Request $request)
    {
        $request->validate(['kode_absen' => 'required|string|size:6']);

        $kode = Str::upper($request->kode_absen);
        $siswa = Auth::user()->siswa;

        $sesiAbsen = SesiAbsen::where('kode_absen', $kode)->first();

        if (!$sesiAbsen) {
            return back()->with('error', 'Kode absensi tidak ditemukan.');
        }

        /*
        $waktuMulai = Carbon::parse($sesiAbsen->tanggal . ' ' . $sesiAbsen->jadwal->jam_mulai);

        if (Carbon::now()->isBefore($waktuMulai)) {
            return back()->with('error', 'Sesi absensi untuk kelas ini belum dimulai.');
        }
        */

        if (Carbon::now()->gt($sesiAbsen->berlaku_hingga)) {
            return back()->with('error', 'Kode absensi sudah kedaluwarsa.');
        }

        if ($sesiAbsen->jadwal->kelas_id != $siswa->kelas_id) {
            return back()->with('error', 'Anda tidak terdaftar di kelas ini.');
        }

        $sudahAbsen = Absensi::where('sesi_absen_id', $sesiAbsen->id)
            ->where('siswa_id', $siswa->id)
            ->where('tanggal', $sesiAbsen->tanggal)
            ->exists();

        if ($sudahAbsen) {
            return back()->with('info', 'Anda sudah melakukan absensi.');
        }

        Absensi::create([
            'sesi_absen_id' => $sesiAbsen->id,
            'siswa_id' => $siswa->id,
            'tanggal' => $sesiAbsen->tanggal,
            'status' => 'hadir',
        ]);

        return back()->with('success', 'Anda berhasil melakukan absensi!');
    }
}
