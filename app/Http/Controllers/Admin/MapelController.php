<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index()
    {
        $mapels = Mapel::all();
        return view('admin.mapel.index', compact('mapels'));
    }

    public function create()
    {
        return view('admin.mapel.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama_mapel' => 'required|string|max:255|unique:mapels,nama_mapel']);
        Mapel::create($request->all());
        return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran baru berhasil ditambahkan.');
    }

    public function edit(Mapel $mapel)
    {
        return view('admin.mapel.edit', compact('mapel'));
    }

    public function update(Request $request, Mapel $mapel)
    {
        $request->validate(['nama_mapel' => 'required|string|max:255|unique:mapels,nama_mapel,' . $mapel->id]);
        $mapel->update($request->all());
        return redirect()->route('admin.mapel.index')->with('success', 'Data mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Mapel $mapel)
    {
        $mapel->delete();
        return redirect()->route('admin.mapel.index')->with('success', 'Data mata pelajaran berhasil dihapus.');
    }
}
