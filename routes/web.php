<?php

use Illuminate\Http\Request;
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
    return view('welcome');
});

// Rute Login terpisah untuk setiap peran
Route::get('login/admin', [AdminLoginController::class, 'create'])->name('login.admin');
Route::post('login/admin', [AdminLoginController::class, 'store'])->name('login.admin.store');

Route::get('login/guru', [GuruLoginController::class, 'create'])->name('login.guru');
Route::post('login/guru', [GuruLoginController::class, 'store'])->name('login.guru.store');

Route::get('login/siswa', [SiswaLoginController::class, 'create'])->name('login.siswa');
Route::post('login/siswa', [SiswaLoginController::class, 'store'])->name('login.siswa.store');

// Route setelah login, dilindungi oleh middleware 'auth'
Route::middleware(['auth', 'verified'])->group(function () {

    // Route ini akan menangkap pengguna setelah login dan mengarahkannya
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route untuk profil pengguna
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
            return view('dashboard', compact('jumlahSiswa', 'jumlahGuru', 'jumlahKelas', 'jumlahMapel'));
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
        Route::get('/absensi/{jadwal}', [GuruAbsensiController::class, 'show'])->name('absensi.show');
        Route::post('/absensi/{sesiAbsen}/kode', [GuruAbsensiController::class, 'createCode'])->name('absensi.createCode');
        Route::post('/absensi/{sesiAbsen}/manual', [GuruAbsensiController::class, 'storeManual'])->name('absensi.storeManual');
    });

    // GRUP ROUTE SISWA
    Route::middleware(['isSiswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaAbsensiController::class, 'dashboard'])->name('dashboard');
        Route::post('/absensi', [SiswaAbsensiController::class, 'store'])->name('absensi.store');
    });

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});

// require __DIR__ . '/auth.php';
