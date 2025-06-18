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
        $kelas10 = Kelas::create(['nama_kelas' => 'X RPL 1', 'tingkat' => 'X']);
        $kelas11 = Kelas::create(['nama_kelas' => 'XI TKJ 2', 'tingkat' => 'XI']);
        $kelas12 = Kelas::create(['nama_kelas' => 'XII AKL 1', 'tingkat' => 'XII']);

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

        // Guru 2
        $userGuru2 = User::create([
            'name' => 'Siti Aminah, M.Kom.',
            'email' => 'siti.guru@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);
        $guru2 = $userGuru2->guru()->create([
            'nip' => '198502022012022002',
            'nama_lengkap' => 'Siti Aminah, M.Kom.',
        ]);

        // Anda bisa tambahkan guru lain dengan cara yang sama...

        // 5. Buat Akun Siswa (lengkap dengan User)
        // Siswa di kelas X RPL 1
        for ($i = 1; $i <= 10; $i++) {
            $userSiswa = User::create([
                'name' => "Siswa Kelas X No. " . $i,
                'email' => "siswa.x." . $i . "@gmail.com",
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]);
            $userSiswa->siswa()->create([
                'nis' => '1000' . $i,
                'nama_lengkap' => "Siswa Kelas X No. " . $i,
                'kelas_id' => $kelas10->id,
            ]);
        }

        // Siswa di kelas XI TKJ 2
        for ($i = 1; $i <= 10; $i++) {
            $userSiswa = User::create([
                'name' => "Siswa Kelas XI No. " . $i,
                'email' => "siswa.xi." . $i . "@gmail.com",
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]);
            $userSiswa->siswa()->create([
                'nis' => '1100' . $i,
                'nama_lengkap' => "Siswa Kelas XI No. " . $i,
                'kelas_id' => $kelas11->id,
            ]);
        }

        // 6. Buat Contoh Jadwal
        Jadwal::create([
            'guru_id' => $guru1->id,
            'mapel_id' => $mapelMTK->id,
            'kelas_id' => $kelas10->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:30:00',
            'jam_selesai' => '09:00:00',
        ]);
        Jadwal::create([
            'guru_id' => $guru2->id,
            'mapel_id' => $mapelPROG->id,
            'kelas_id' => $kelas10->id,
            'hari' => 'Senin',
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '10:30:00',
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
