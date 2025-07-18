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
            'name' => 'Admin Aplikasi',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'), // Ganti 'password' dengan password yang aman
            'role' => 'admin',
        ]);

        // 2. Buat Data Kelas
        $kelas10 = Kelas::create(['nama_kelas' => 'IPA 1', 'tingkat' => 'X']);
        $kelas11 = Kelas::create(['nama_kelas' => 'IPS 1', 'tingkat' => 'XI']);
        $kelas12 = Kelas::create(['nama_kelas' => 'IPS 2', 'tingkat' => 'XII']);

        // 3. Buat Data Mata Pelajaran
        $mapelMTK = Mapel::create(['nama_mapel' => 'Matematika Wajib']);
        $mapelBIN = Mapel::create(['nama_mapel' => 'Bahasa Indonesia']);
        $mapelBIG = Mapel::create(['nama_mapel' => 'Bahasa Inggris']);
        $mapelPROG = Mapel::create(['nama_mapel' => 'Dasar Pemrograman']);

        // 4. Buat Akun Guru (lengkap dengan User)
        // Guru 1
        $userGuru1 = User::create([
            'name' => 'Budi Santoso, S.Pd.',
            'email' => 'budi.guru@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);
        $guru1 = $userGuru1->guru()->create([
            'nip' => '198001012010011001',
            'nama_lengkap' => 'Budi Santoso, S.Pd.',
        ]);

        // 6. Contoh Jadwal
        Jadwal::create([
            'guru_id' => $guru1->id,
            'mapel_id' => $mapelMTK->id,
            'kelas_id' => $kelas10->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:30:00',
            'jam_selesai' => '09:00:00',
        ]);
        Jadwal::create([
            'guru_id' => $guru1->id,
            'mapel_id' => $mapelBIN->id,
            'kelas_id' => $kelas11->id,
            'hari' => 'Selasa',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '09:30:00',
        ]);
    }
}
