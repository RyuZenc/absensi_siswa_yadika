<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleAssignmentController extends Controller
{
    public function index()
    {
        // Mengambil semua guru yang bisa menjadi wali kelas (yang role-nya 'guru' atau sudah 'wali_kelas')
        $availableWaliKelas = Guru::whereIn('role', ['guru', 'wali_kelas'])->get();
        // Mengambil semua kelas
        $kelas = Kelas::orderByRaw("FIELD(tingkat, 'X', 'XI', 'XII')")->get();
        // Mengambil semua penugasan wali kelas yang sudah ada
        $waliKelasAssignments = Kelas::with('waliKelas')
            ->whereNotNull('guru_id')
            ->orderByRaw("FIELD(tingkat, 'X', 'XI', 'XII')")
            ->get();

        return view('admin.roles.assign', compact('availableWaliKelas', 'kelas', 'waliKelasAssignments'));
    }

    public function assignWaliKelas(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            // Kelas hanya bisa diampu oleh satu guru (wali kelas)
            'kelas_id' => 'required|exists:kelas,id|unique:kelas,guru_id,NULL,id',
        ], [
            'kelas_id.unique' => 'Kelas ini sudah memiliki Wali Kelas.',
        ]);

        try {
            DB::beginTransaction();

            $guru = Guru::findOrFail($request->guru_id);
            $kelas = Kelas::findOrFail($request->kelas_id);

            // Jika guru yang dipilih sudah menjadi wali kelas untuk kelas lain, lepaskan dari kelas tersebut
            if ($guru->kelasYangDiampu) {
                $guru->kelasYangDiampu->update(['guru_id' => null]);
            }

            // Jika kelas sudah punya wali kelas lain, kembalikan peran wali kelas sebelumnya menjadi 'guru' biasa
            if ($kelas->waliKelas) {
                $kelas->waliKelas->update(['role' => 'guru']);
            }

            // Tetapkan guru sebagai wali kelas
            $guru->update(['role' => 'wali_kelas']);

            // Tetapkan kelas ke guru ini
            $kelas->update(['guru_id' => $guru->id]);

            DB::commit();

            return redirect()->route('admin.roles.assign')->with('success', 'Wali Kelas berhasil ditetapkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menetapkan Wali Kelas: ' . $e->getMessage());
        }
    }

    public function removeWaliKelas(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        try {
            DB::beginTransaction();
            $kelas = Kelas::with('waliKelas')->findOrFail($request->kelas_id);

            if ($kelas->waliKelas) {
                $kelas->waliKelas->update(['role' => 'guru']); // Turunkan peran guru menjadi 'guru' biasa
            }
            $kelas->update(['guru_id' => null]); // Lepaskan kelas dari wali kelas

            DB::commit();
            return redirect()->route('admin.roles.assign')->with('success', 'Wali Kelas berhasil dihapus dari kelas ini.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus Wali Kelas: ' . $e->getMessage());
        }
    }
}
