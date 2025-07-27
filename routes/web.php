<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\LaporanAbsensiController;
use App\Http\Controllers\Guru\AbsensiController as GuruAbsensiController;
use App\Http\Controllers\Siswa\AbsensiController as SiswaAbsensiController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\GuruLoginController;
use App\Http\Controllers\Auth\SiswaLoginController;
use App\Http\Controllers\Admin\RoleAssignmentController;
use App\Http\Controllers\Guru\RekapController as GuruRekapController;
use App\Http\Controllers\WaliKelas\RekapController as WaliKelasRekapController;
use App\Http\Controllers\WaliKelas\AbsensiController;


// Route untuk halaman awal
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'guru' => redirect()->route('guru.dashboard'),
            'siswa' => redirect()->route('siswa.dashboard'),
            default => redirect()->route('dashboard'),
        };
    }

    return view('welcome');
});

// --- RUTE LOGIN PUBLIK ---
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminLoginController::class, 'create'])->middleware('guest')->name('login');
    Route::post('login', [AdminLoginController::class, 'store'])->middleware('guest')->name('login.store');
});

Route::prefix('guru')->name('guru.')->group(function () {
    Route::get('login', [GuruLoginController::class, 'create'])->middleware('guest')->name('login');
    Route::post('login', [GuruLoginController::class, 'store'])->middleware('guest')->name('login.store');
});

Route::prefix('siswa')->name('siswa.')->group(function () {
    Route::get('login', [SiswaLoginController::class, 'create'])->middleware('guest')->name('login');
    Route::post('login', [SiswaLoginController::class, 'store'])->middleware('guest')->name('login.store');
});

// --- RUTE YANG MEMERLUKAN AUTENTIKASI ---
Route::middleware(['auth', 'verified'])->group(function () {

    // Pengarah Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // GRUP ROUTE ADMIN
    Route::middleware(['isAdmin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            $jumlahSiswa = \App\Models\Siswa::count();
            $jumlahGuru = \App\Models\Guru::count();
            $jumlahKelas = \App\Models\Kelas::count();
            $jumlahMapel = \App\Models\Mapel::count();
            return view('admin.dashboard', compact('jumlahSiswa', 'jumlahGuru', 'jumlahKelas', 'jumlahMapel'));
        })->name('dashboard');

        Route::post('/guru/import', [GuruController::class, 'import'])->name('guru.import');
        Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');

        Route::resource('jadwal', JadwalController::class);
        Route::resource('kelas', KelasController::class);
        Route::resource('mapel', MapelController::class);
        Route::resource('guru', GuruController::class);
        Route::resource('siswa', SiswaController::class);

        Route::get('laporan-absensi', [LaporanAbsensiController::class, 'index'])->name('laporan.absensi.index');
        Route::get('laporan-absensi/export', [LaporanAbsensiController::class, 'export'])->name('laporan.absensi.export');

        Route::get('/roles/assign', [RoleAssignmentController::class, 'index'])->name('roles.assign');
        Route::post('/roles/assign-wali-kelas', [RoleAssignmentController::class, 'assignWaliKelas'])->name('roles.assignWaliKelas');
        Route::delete('/roles/remove-wali-kelas', [RoleAssignmentController::class, 'removeWaliKelas'])->name('roles.removeWaliKelas');
    });

    // GRUP ROUTE GURU
    Route::middleware(['isGuru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [GuruAbsensiController::class, 'dashboard'])->name('dashboard');
        Route::get('/kelas', [GuruAbsensiController::class, 'daftarKelas'])->name('kelas.index');
        Route::get('/absensi/{jadwal}', [GuruAbsensiController::class, 'show'])->name('absensi.show');
        Route::post('/absensi/{sesiAbsen}/kode', [GuruAbsensiController::class, 'createCode'])->name('absensi.createCode');
        Route::post('/absensi/{sesiAbsen}/manual', [GuruAbsensiController::class, 'storeManual'])->name('absensi.storeManual');
        Route::get('/absensi/{sesiAbsen}/export', [GuruAbsensiController::class, 'export'])->name('absensi.export');
        Route::post('/absensi/update-status', [GuruAbsensiController::class, 'updateStatus'])->name('absensi.updateStatus');

        Route::get('/guru/riwayat/{id}', [GuruAbsensiController::class, 'detail'])->name('guru.riwayat.detail');
        Route::get('/riwayat-absensi', [GuruAbsensiController::class, 'riwayat'])->name('riwayat.riwayat');
        Route::get('/riwayat-absensi/{id}', [GuruAbsensiController::class, 'detail'])->name('riwayat.detail');

        Route::get('/rekap', [GuruRekapController::class, 'index'])->name('rekap.index');
        Route::get('/rekap/export', [GuruRekapController::class, 'export'])->name('rekap.export');
    });

    Route::middleware(['auth', 'verified', 'isWaliKelas'])->prefix('walikelas')->name('walikelas.')->group(function () {
        Route::get('/cek-kelas', [AbsensiController::class, 'cekKelas'])->name('cek_kelas');
        Route::get('/laporan/absensi/export', [AbsensiController::class, 'export'])->name('laporan.absensi.export.walikelas');
        Route::get('/rekap', [WaliKelasRekapController::class, 'index'])->name('rekap.index');
        Route::get('/rekap/export', [WaliKelasRekapController::class, 'export'])->name('rekap.export');
    });

    // GRUP ROUTE SISWA
    Route::middleware(['isSiswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaAbsensiController::class, 'dashboard'])->name('dashboard');
        Route::post('/absensi', [SiswaAbsensiController::class, 'store'])->name('absensi.store');
        Route::get('/jadwal', [SiswaAbsensiController::class, 'jadwal'])->name('jadwal.index');
    });
});

require __DIR__ . '/auth.php';
