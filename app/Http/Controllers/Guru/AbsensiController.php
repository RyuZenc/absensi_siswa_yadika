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
use App\Exports\AbsensiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

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

        // Ambil semua jadwal unik berdasarkan kelas dan mapel untuk guru ini
        $jadwals = Jadwal::with(['kelas', 'mapel'])
            ->where('guru_id', $guruId)
            ->orderBy('kelas_id')
            ->orderBy('mapel_id')
            ->get()
            ->unique(function ($item) {
                return $item['kelas_id'] . '-' . $item['mapel_id'];
            });

        return view('guru.kelas.index', compact('jadwals'));
    }

    public function show(Jadwal $jadwal)
    {
        $tanggalHariIni = Carbon::today()->toDateString();

        // Cari atau buat sesi absensi untuk jadwal ini pada hari ini
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

        // Ambil semua siswa di kelas yang sesuai dengan jadwal
        $siswas = $jadwal->kelas->siswas()->orderBy('nama_lengkap')->get();

        // Ambil data absensi yang sudah ada untuk sesi ini
        $absensiSudahAda = Absensi::where('sesi_absen_id', $sesiAbsen->id)
            ->pluck('status', 'siswa_id');

        return view('guru.absensi.show', compact('jadwal', 'sesiAbsen', 'siswas', 'absensiSudahAda'));
    }

    public function createCode(Request $request, SesiAbsen $sesiAbsen)
    {

        $request->validate([
            'durasi' => 'required|integer|min:1|max:60', // Durasi 1-60 menit
        ]);

        $durasiMenit = (int) $request->input('durasi');

        $waktuSekarang = Carbon::now();
        $waktuBatasDurasi = $waktuSekarang->copy()->addMinutes($durasiMenit);

        // Ambil jam selesai dari jadwal terkait
        $jamSelesaiJadwal = Carbon::parse($sesiAbsen->tanggal . ' ' . $sesiAbsen->jadwal->jam_selesai);

        // Tentukan waktu berlaku yang sebenarnya: waktu yang paling cepat antara (sekarang + durasi) dan jam selesai pelajaran.
        $waktuBerlakuSebenarnya = $waktuBatasDurasi->isBefore($jamSelesaiJadwal) ? $waktuBatasDurasi : $jamSelesaiJadwal;

        $sesiAbsen->update([
            'kode_absen' => Str::upper(Str::random(6)),
            'berlaku_hingga' => $waktuBerlakuSebenarnya
        ]);

        return back()->with('success', "Kode absensi berhasil dibuat dan berlaku selama {$durasiMenit} menit (atau hingga jam pelajaran selesai).");
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

    public function cancelCode(SesiAbsen $sesiAbsen)
    {
        $sesiAbsen->update([
            'kode_absen' => null,
            'berlaku_hingga' => Carbon::now(),
        ]);

        return back()->with('success', 'Kode absensi berhasil dibatalkan.');
    }

    public function exportExcel($id)
    {
        $sesi = \App\Models\SesiAbsen::with(['absensis.siswa', 'jadwal.mapel', 'jadwal.kelas'])->findOrFail($id);
        $filename = 'absensi_' . $sesi->jadwal->kelas->nama_kelas . '_' . $sesi->tanggal . '.xlsx';

        return Excel::download(new AbsensiExport($sesi), $filename);
    }
}
