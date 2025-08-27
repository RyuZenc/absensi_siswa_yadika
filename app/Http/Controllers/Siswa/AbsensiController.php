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
        try {
            $hariIni = Carbon::now()->isoFormat('dddd');
            $siswa = Auth::user()->siswa;

            if (!$siswa) {
                return redirect()->route('login')->with('error', 'Data siswa tidak ditemukan. Silakan hubungi administrator.');
            }

            $jadwals = Jadwal::where('kelas_id', $siswa->kelas_id)
                ->where('hari', $hariIni)
                ->orderBy('jam_mulai', 'asc')
                ->get();
            $riwayatAbsensi = Absensi::where('siswa_id', $siswa->id)
                ->latest()
                ->take(5)
                ->get();
            return view('siswa.dashboard', compact('jadwals', 'riwayatAbsensi'));
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat memuat dashboard. Silakan login ulang.');
        }
    }
    public function store(Request $request)
    {
        try {
            $request->validate(['kode_absen' => 'required|string|size:6']);
            $kode = Str::upper($request->kode_absen);
            $siswa = Auth::user()->siswa;

            if (!$siswa) {
                return redirect()->route('login')->with('error', 'Data siswa tidak ditemukan. Silakan hubungi administrator.');
            }

            $sesiAbsen = SesiAbsen::where('kode_absen', $kode)->first();
            if (!$sesiAbsen) {
                return back()->with('error', 'Kode absensi tidak ditemukan.');
            }
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
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat melakukan absensi.');
        }
    }
    public function jadwal()
    {
        try {
            $siswa = Auth::user()->siswa;

            if (!$siswa) {
                return redirect()->route('login')->with('error', 'Data siswa tidak ditemukan. Silakan hubungi administrator.');
            }

            $jadwals = \App\Models\Jadwal::with(['mapel', 'guru'])
                ->where('kelas_id', $siswa->kelas_id)
                ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
                ->orderBy('jam_mulai')
                ->get()
                ->groupBy('hari');
            return view('siswa.jadwal.index', compact('jadwals'));
        } catch (\Exception $e) {
            return redirect()->route('siswa.dashboard')->with('error', 'Terjadi kesalahan saat memuat jadwal.');
        }
    }
}
