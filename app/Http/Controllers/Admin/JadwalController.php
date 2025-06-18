<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Kelas;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::with(['guru', 'mapel', 'kelas'])->orderBy('hari')->orderBy('jam_mulai')->get();
        return view('admin.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        // Mengambil semua data master yang dibutuhkan untuk form
        $gurus = Guru::orderBy('nama_lengkap')->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.jadwal.create', compact('gurus', 'mapels', 'kelas'));
    }

    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'mapel_id' => 'required|exists:mapels,id',
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        Jadwal::create($request->all());
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal baru berhasil ditambahkan.');
    }

    public function show(Jadwal $jadwal)
    {
        // Mengambil data jadwal beserta relasinya untuk ditampilkan
        $jadwal->load(['guru', 'mapel', 'kelas.siswas']);
        return view('admin.jadwal.show', compact('jadwal'));
    }

    public function edit(Jadwal $jadwal)
    {
        // Mengambil semua data master untuk mengisi dropdown pada form edit
        $gurus = Guru::orderBy('nama_lengkap')->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.jadwal.edit', compact('jadwal', 'gurus', 'mapels', 'kelas'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        // Validasi input dari form edit
        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'mapel_id' => 'required|exists:mapels,id',
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $jadwal->update($request->all());
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        // Menghapus data jadwal dari database
        try {
            $jadwal->delete();
            return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.jadwal.index')->with('error', 'Gagal menghapus jadwal. Masih ada data terkait.');
        }
    }
}
