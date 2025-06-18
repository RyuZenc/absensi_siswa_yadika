<?php

namespace App\Http\Controllers\Admin;

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with(['user', 'kelas'])->get();
        return view('admin.siswa.index', compact('siswas'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:255', 'unique:siswas'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'siswa',
            ]);

            $user->siswa()->create([
                'nama_lengkap' => $request->nama_lengkap,
                'nis' => $request->nis,
                'kelas_id' => $request->kelas_id
            ]);
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa baru berhasil ditambahkan.');
    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:255', 'unique:siswas,nis,' . $siswa->id],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $siswa->user_id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::transaction(function () use ($request, $siswa) {
            $siswa->user()->update([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $siswa->user()->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            $siswa->update([
                'nama_lengkap' => $request->nama_lengkap,
                'nis' => $request->nis,
                'kelas_id' => $request->kelas_id
            ]);
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        DB::transaction(function () use ($siswa) {
            $siswa->user()->delete();
            $siswa->delete();
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
