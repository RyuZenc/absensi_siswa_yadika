<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Jadwal;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Nonaktifkan pengecekan foreign key untuk sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Kosongkan tabel sebelum seeding untuk menghindari duplikat
        User::truncate();
        Kelas::truncate();
        Mapel::truncate();
        Guru::truncate();
        Siswa::truncate();
        Jadwal::truncate();
        // Tambahkan tabel lain jika perlu dikosongkan

        // Aktifkan kembali pengecekan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Buat Akun Admin
        User::create([
            'name' => 'Admin Yadika',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Buat Data Kelas
        Kelas::create(['nama_kelas' => 'E1', 'tingkat' => 'X']);
        Kelas::create(['nama_kelas' => 'E2', 'tingkat' => 'X']);
        Kelas::create(['nama_kelas' => 'F1', 'tingkat' => 'XI']);
        Kelas::create(['nama_kelas' => 'F2', 'tingkat' => 'XI']);
        Kelas::create(['nama_kelas' => 'F1', 'tingkat' => 'XII']);
        Kelas::create(['nama_kelas' => 'F2', 'tingkat' => 'XII']);

        // 3. Buat Data Mata Pelajaran
        Mapel::create(['nama_mapel' => 'Matematika']);
        Mapel::create(['nama_mapel' => 'Bahasa Indonesia']);
        Mapel::create(['nama_mapel' => 'Bahasa Inggris']);
        Mapel::create(['nama_mapel' => 'Fisika']);

        // 4. Buat Akun Guru (lengkap dengan User)
        // Guru 1
        User::create([
            'name' => 'Budi Santoso, S.Pd.',
            'email' => 'budi.guru@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);
    }
}
