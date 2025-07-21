<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Imports\GuruImport;
use Maatwebsite\Excel\Facades\Excel;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Guru::with('user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('email', 'like', "%{$search}%")->orWhere('username', 'like', "%{$search}%");
                    });
            });
        }

        $gurus = $query->paginate(15);
        $gurus->appends(['search' => $search]);

        return view('admin.guru.index', compact('gurus', 'search'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:255', 'unique:gurus'],
            'username' => ['required', 'string', 'max:255', 'unique:users', 'alpha_dash'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->nama_lengkap,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'guru',
            ]);

            $user->guru()->create([
                'nama_lengkap' => $request->nama_lengkap,
                'nip' => $request->nip,
            ]);
        });

        return redirect()->route('admin.guru.index')->with('success', 'Data guru baru berhasil ditambahkan.');
    }

    public function edit(Guru $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:255', 'unique:gurus,nip,' . $guru->id],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $guru->user_id, 'alpha_dash'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $guru->user_id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::transaction(function () use ($request, $guru) {
            $guru->user()->update([
                'name' => $request->nama_lengkap,
                'username' => $request->username,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $guru->user()->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            $guru->update([
                'nama_lengkap' => $request->nama_lengkap,
                'nip' => $request->nip,
            ]);
        });

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Request $request, Guru $guru)
    {
        DB::transaction(function () use ($guru) {
            $guru->user()->delete();
            $guru->delete();
        });

        return redirect()->route('admin.guru.index', ['search' => $request->input('search')])
            ->with('success', 'Data guru berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        try {
            Excel::import(new GuruImport, $request->file('file'));
        } catch (\Exception $e) {
            return redirect()->route('admin.guru.index')->with('error', 'Gagal mengimpor data. Pastikan format file CSV sudah benar dan tidak ada data duplikat. Error: ' . $e->getMessage());
        }

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diimpor!');
    }
}
