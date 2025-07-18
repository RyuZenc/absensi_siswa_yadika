<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Import Rule untuk validasi

class KelasController extends Controller
{
    // Definisikan pilihan tingkat di satu tempat
    private $tingkatOptions = ['X', 'XI', 'XII'];

    public function index()
    {
        $kelas = Kelas::withCount('siswas')->get();
        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('admin.kelas.create', ['tingkats' => $this->tingkatOptions]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas',
            'tingkat' => ['required', Rule::in($this->tingkatOptions)],
        ]);
        Kelas::create($request->all());
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas baru berhasil ditambahkan.');
    }

    public function edit(Kelas $kela) // Variabel $kela akan diubah menjadi $kelas
    {
        return view('admin.kelas.edit', [
            'kelas' => $kela,
            'tingkats' => $this->tingkatOptions
        ]);
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas,' . $kela->id,
            'tingkat' => ['required', Rule::in($this->tingkatOptions)],
        ]);
        $kela->update($request->all());
        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        if ($kela->siswas()->count() > 0) {
            return redirect()->route('admin.kelas.index')->with('error', 'Gagal menghapus. Masih ada siswa di kelas ini.');
        }
        $kela->delete();
        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil dihapus.');
    }
}
