<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use App\Models\Guru;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index()
    {
        $mapels = Mapel::with('guru')->get();
        return view('admin.mapel.index', compact('mapels'));
    }

    public function create()
    {
        $gurus = Guru::orderBy('nama_lengkap')->get();
        return view('admin.mapel.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255|unique:mapels,nama_mapel',
            'guru_id' => 'nullable|exists:gurus,id',
        ]);
        Mapel::create($request->all());
        return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran baru berhasil ditambahkan.');
    }

    public function edit(Mapel $mapel)
    {
        $gurus = Guru::orderBy('nama_lengkap')->get();
        return view('admin.mapel.edit', compact('mapel', 'gurus'));
    }

    public function update(Request $request, Mapel $mapel)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255|unique:mapels,nama_mapel,' . $mapel->id,
            'guru_id' => 'nullable|exists:gurus,id',
        ]);
        $mapel->update($request->all());
        return redirect()->route('admin.mapel.index')->with('success', 'Data mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Mapel $mapel)
    {
        // Before deleting a mapel, consider if it's used in any jadwal.
        // The onDelete('cascade') on jadwal's mapel_id will handle related jadwals.
        try {
            $mapel->delete();
            return redirect()->route('admin.mapel.index')->with('success', 'Data mata pelajaran berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.mapel.index')->with('error', 'Gagal menghapus mata pelajaran. Pastikan tidak ada jadwal yang terkait.');
        }
    }
}
