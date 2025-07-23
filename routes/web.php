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
use App\Http\Controllers\Guru\AbsensiController as GuruAbsensiController;
use App\Http\Controllers\Siswa\AbsensiController as SiswaAbsensiController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\GuruLoginController;
use App\Http\Controllers\Auth\SiswaLoginController;


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
    });

    // GRUP ROUTE GURU
    Route::middleware(['isGuru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [GuruAbsensiController::class, 'dashboard'])->name('dashboard');
        Route::get('/kelas', [GuruAbsensiController::class, 'daftarKelas'])->name('kelas.index');
        Route::get('/absensi/{jadwal}', [GuruAbsensiController::class, 'show'])->name('absensi.show');
        Route::get('/absensi/export/{id}', [\App\Http\Controllers\Guru\AbsensiController::class, 'exportExcel'])->name('absensi.export');
        Route::post('/absensi/{sesiAbsen}/kode', [GuruAbsensiController::class, 'createCode'])->name('absensi.createCode');
        Route::post('/absensi/{sesiAbsen}/batal-kode', [GuruAbsensiController::class, 'cancelCode'])->name('absensi.cancelCode');
        Route::post('/absensi/{sesiAbsen}/manual', [GuruAbsensiController::class, 'storeManual'])->name('absensi.storeManual');
    });

    // GRUP ROUTE SISWA
    Route::middleware(['isSiswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaAbsensiController::class, 'dashboard'])->name('dashboard');
        Route::post('/absensi', [SiswaAbsensiController::class, 'store'])->name('absensi.store');
    });
});

require __DIR__ . '/auth.php';
