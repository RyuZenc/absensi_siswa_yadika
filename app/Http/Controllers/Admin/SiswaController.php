<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Siswa::with(['user', 'kelas']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('kelas', function ($kelasQuery) use ($search) {
                        $kelasQuery->where('tingkat', 'like', "%{$search}%")
                            ->orWhere('nama_kelas', 'like', "%{$search}%");
                    });
            });
        }

        $siswas = $query->orderBy('created_at', 'desc')->paginate(20);
        $siswas->appends(['search' => $search]);

        return view('admin.siswa.index', compact('siswas', 'search'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('admin.siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:255', 'unique:siswas'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::min(6)],
        ]);

        try {
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
        } catch (\Exception $e) {
            return redirect()->route('admin.siswa.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:255', 'unique:siswas,nis,' . $siswa->id],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $siswa->user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::min(6)],
        ]);

        try {
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
        } catch (\Exception $e) {
            return redirect()->route('admin.siswa.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, Siswa $siswa)
    {
        try {
            DB::transaction(function () use ($siswa) {
                // Hapus siswa terlebih dahulu untuk menjaga referential integrity
                $user = $siswa->user;
                $siswa->delete();
                $user->delete();
            });

            return redirect()->route('admin.siswa.index', ['search' => $request->input('search')])
                ->with('success', 'Data siswa berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.siswa.index', ['search' => $request->input('search')])
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Method untuk mengimpor data siswa dari file CSV dengan logika baru.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $import = new SiswaImport;

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Exception $e) {
            return redirect()->route('admin.siswa.index')->with('error', 'Terjadi error saat memproses file: ' . $e->getMessage());
        }

        $notFoundClasses = $import->getNotFoundClasses();

        if (!empty($notFoundClasses)) {
            $errorMessage = 'Beberapa data gagal diimpor karena nama kelas berikut tidak ditemukan di database: ' . implode(', ', $notFoundClasses);
            return redirect()->route('admin.siswa.index')->with('error', $errorMessage);
        }

        $importedCount = $import->getImportedRowCount();
        if ($importedCount > 0) {
            return redirect()->route('admin.siswa.index')->with('success', $importedCount . ' data siswa berhasil diimpor!');
        } else {
            return redirect()->route('admin.siswa.index')->with('error', 'Tidak ada data baru yang diimpor. Periksa kembali isi file CSV Anda, pastikan tidak ada data duplikat dan nama kelas sudah benar.');
        }
    }
}
