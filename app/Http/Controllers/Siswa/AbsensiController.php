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
    // Menampilkan dashboard siswa dengan jadwal dan riwayat
    public function dashboard()
    {
        $hariIni = Carbon::now()->isoFormat('dddd');
        $siswa = Auth::user()->siswa;

        // Ambil jadwal pelajaran siswa hari ini
        $jadwals = Jadwal::where('kelas_id', $siswa->kelas_id)
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        // Ambil 5 riwayat absensi terakhir
        $riwayatAbsensi = Absensi::where('siswa_id', $siswa->id)
            ->latest() // Mengurutkan dari yang terbaru
            ->take(5) // Mengambil 5 data terakhir
            ->get();

        return view('siswa.dashboard', compact('jadwals', 'riwayatAbsensi'));
    }

    // Proses absensi mandiri dengan kode
    public function store(Request $request)
    {
        $request->validate(['kode_absen' => 'required|string|size:6']);

        $kode = Str::upper($request->kode_absen);
        $siswa = Auth::user()->siswa;

        // 1. Cari sesi absensi berdasarkan kode
        $sesiAbsen = SesiAbsen::where('kode_absen', $kode)->first();

        // 2. Validasi sesi
        if (!$sesiAbsen) {
            return back()->with('error', 'Kode absensi tidak ditemukan.');
        }

        if (Carbon::now()->gt($sesiAbsen->berlaku_hingga)) {
            return back()->with('error', 'Kode absensi sudah kedaluwarsa.');
        }

        // 3. Validasi apakah siswa termasuk dalam kelas di jadwal sesi tersebut
        if ($sesiAbsen->jadwal->kelas_id != $siswa->kelas_id) {
            return back()->with('error', 'Anda tidak terdaftar di kelas ini.');
        }

        // 4. Simpan absensi
        Absensi::updateOrCreate(
            [
                'sesi_absen_id' => $sesiAbsen->id,
                'siswa_id' => $siswa->id,
                'tanggal' => $sesiAbsen->tanggal,
            ],
            [
                'status' => 'hadir',
            ]
        );

        return back()->with('success', 'Anda berhasil melakukan absensi!');
    }
}
