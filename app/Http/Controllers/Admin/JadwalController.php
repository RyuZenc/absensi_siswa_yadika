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
        $jadwals = Jadwal::with(['guru', 'mapel', 'kelas'])
            ->join('kelas', 'jadwals.kelas_id', '=', 'kelas.id')
            ->orderBy('kelas.tingkat')
            ->orderBy('kelas.nama_kelas')
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->select('jadwals.*')
            ->get()
            ->groupBy('kelas_id');

        return view('admin.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        $gurus = Guru::orderBy('nama_lengkap')->get();
        $mapels = Mapel::with('guru')->orderBy('nama_mapel')->get();
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('admin.jadwal.create', compact('gurus', 'mapels', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'mapel_id' => 'required|exists:mapels,id',
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $guruBentrok = Jadwal::where('guru_id', $request->guru_id)
            ->where('hari', $request->hari)
            ->where(function ($query) use ($request) {
                $query->where('jam_mulai', '<', $request->jam_selesai)
                    ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->exists();

        if ($guruBentrok) {
            return back()->withInput()->withErrors(['guru_id' => 'Guru sudah memiliki jadwal pada waktu tersebut.']);
        }

        Jadwal::create($request->all());
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal baru berhasil ditambahkan.');
    }

    public function show(Jadwal $jadwal)
    {
        $jadwal->load(['guru', 'mapel', 'kelas.siswas']);
        return view('admin.jadwal.show', compact('jadwal'));
    }

    public function edit(Jadwal $jadwal)
    {
        $gurus = Guru::orderBy('nama_lengkap')->get();
        $mapels = Mapel::with('guru')->orderBy('nama_mapel')->get();
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('admin.jadwal.edit', compact('jadwal', 'gurus', 'mapels', 'kelas'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'mapel_id' => 'required|exists:mapels,id',
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $guruBentrok = Jadwal::where('guru_id', $request->guru_id)
            ->where('hari', $request->hari)
            ->where('id', '!=', $jadwal->id)
            ->where(function ($query) use ($request) {
                $query->where('jam_mulai', '<', $request->jam_selesai)
                    ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->exists();

        if ($guruBentrok) {
            return back()->withInput()->withErrors(['guru_id' => 'Guru sudah memiliki jadwal pada waktu tersebut.']);
        }

        $jadwal->update($request->all());
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        try {
            $jadwal->delete();
            return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.jadwal.index')->with('error', 'Gagal menghapus jadwal. Masih ada data terkait.');
        }
    }
}
