<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

// Import semua controller yang dibutuhkan
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Guru\AbsensiController as GuruAbsensiController;
use App\Http\Controllers\Siswa\AbsensiController as SiswaAbsensiController;


// Route untuk halaman awal
Route::get('/', function () {
    return view('welcome');
});

// Route setelah login, dilindungi oleh middleware 'auth'
// Menggunakan 'verified' untuk memastikan email sudah diverifikasi jika fitur itu aktif
Route::middleware(['auth', 'verified'])->group(function () {

    // Arahkan ke dashboard yang sesuai berdasarkan peran setelah login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route untuk profil pengguna (bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // =============================================================
    // GRUP ROUTE UNTUK ADMIN
    // =============================================================
    Route::middleware(['isAdmin'])->prefix('admin')->name('admin.')->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', function () {
            // Logika untuk mengambil data statistik, dll. bisa ditambahkan di sini
            $jumlahSiswa = \App\Models\Siswa::count();
            $jumlahGuru = \App\Models\Guru::count();
            $jumlahKelas = \App\Models\Kelas::count();
            $jumlahMapel = \App\Models\Mapel::count();
            return view('dashboard', compact('jumlahSiswa', 'jumlahGuru', 'jumlahKelas', 'jumlahMapel'));
        })->name('dashboard');

        // CRUD untuk semua data master
        Route::resource('jadwal', JadwalController::class);
        Route::resource('kelas', KelasController::class);
        Route::resource('mapel', MapelController::class);
        Route::resource('guru', GuruController::class);
        Route::resource('siswa', SiswaController::class);
    });

    // =============================================================
    // GRUP ROUTE UNTUK GURU
    // =============================================================
    Route::middleware(['isGuru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [GuruAbsensiController::class, 'dashboard'])->name('dashboard');
        Route::get('/absensi/{jadwal}', [GuruAbsensiController::class, 'show'])->name('absensi.show');
        Route::post('/absensi/{sesiAbsen}/kode', [GuruAbsensiController::class, 'createCode'])->name('absensi.createCode');
        Route::post('/absensi/{sesiAbsen}/manual', [GuruAbsensiController::class, 'storeManual'])->name('absensi.storeManual');
    });

    // =============================================================
    // GRUP ROUTE UNTUK SISWA
    // =============================================================
    Route::middleware(['isSiswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaAbsensiController::class, 'dashboard'])->name('dashboard');
        Route::post('/absensi', [SiswaAbsensiController::class, 'store'])->name('absensi.store');
    });
});

// Memuat route otentikasi (login, register, logout, dll.) dari file auth.php
require __DIR__ . '/auth.php';
