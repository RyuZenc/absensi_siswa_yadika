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
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        User::truncate();
        Kelas::truncate();
        Mapel::truncate();
        Guru::truncate();
        Siswa::truncate();
        Jadwal::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Admin
        User::create([
            'name' => 'Admin Aplikasi',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Kelas
        $kelas = [
            'X IPA 1' => Kelas::create(['nama_kelas' => 'IPA 1', 'tingkat' => 'X']),
            'XI IPS 1' => Kelas::create(['nama_kelas' => 'IPS 1', 'tingkat' => 'XI']),
            'XII IPS 2' => Kelas::create(['nama_kelas' => 'IPS 2', 'tingkat' => 'XII']),
        ];

        // 3. Mata Pelajaran
        $mapel = [
            'Matematika' => Mapel::create(['nama_mapel' => 'Matematika Wajib']),
            'B. Indonesia' => Mapel::create(['nama_mapel' => 'Bahasa Indonesia']),
            'B. Inggris' => Mapel::create(['nama_mapel' => 'Bahasa Inggris']),
            'Pemrograman' => Mapel::create(['nama_mapel' => 'Dasar Pemrograman']),
            'PKN' => Mapel::create(['nama_mapel' => 'Pendidikan Kewarganegaraan']),
        ];

        // 4. Guru + User
        $guruList = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'nip' => '198001012010011001'
            ],
            [
                'name' => 'Rina Sari',
                'email' => 'rina@gmail.com',
                'nip' => '198203052011022002'
            ],
            [
                'name' => 'Joko Anwar',
                'email' => 'joko@gmail.com',
                'nip' => '197912232009031003'
            ],
        ];

        $guruModelList = [];

        foreach ($guruList as $guruData) {
            $user = User::create([
                'name' => $guruData['name'],
                'email' => $guruData['email'],
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]);

            $guru = $user->guru()->create([
                'nama_lengkap' => $guruData['name'],
                'nip' => $guruData['nip'],
            ]);

            $guruModelList[] = $guru;
        }

        // 5. Siswa + User
        $siswaList = [
            [
                'nama' => 'Dina Putri',
                'email' => 'dina@mail.com',
                'password' => 'rahasia',
                'nis' => '20230001',
                'kelas' => $kelas['XI IPS 1']->id,
            ],
            [
                'nama' => 'Adi Wijaya',
                'email' => 'adi@gmail.com',
                'password' => '123456',
                'nis' => '20230002',
                'kelas' => $kelas['XII IPS 2']->id,
            ],
            [
                'nama' => 'Sari Melati',
                'email' => 'sari@gmail.com',
                'password' => '123123',
                'nis' => '20230003',
                'kelas' => $kelas['X IPA 1']->id,
            ],
        ];

        foreach ($siswaList as $siswaData) {
            $user = User::create([
                'name' => $siswaData['nama'],
                'email' => $siswaData['email'],
                'password' => Hash::make($siswaData['password']),
                'role' => 'siswa',
            ]);

            $user->siswa()->create([
                'nama_lengkap' => $siswaData['nama'],
                'nis' => $siswaData['nis'],
                'kelas_id' => $siswaData['kelas'],
            ]);
        }

        // 6. Jadwal Otomatis
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jam = [
            ['07:30:00', '09:00:00'],
            ['09:15:00', '10:45:00'],
            ['11:00:00', '12:30:00'],
        ];

        foreach ($kelas as $kelasObj) {
            foreach ($mapel as $mapelObj) {
                $randomGuru = $guruModelList[array_rand($guruModelList)];
                $randomHari = $days[array_rand($days)];
                $randomJam = $jam[array_rand($jam)];

                Jadwal::create([
                    'guru_id' => $randomGuru->id,
                    'mapel_id' => $mapelObj->id,
                    'kelas_id' => $kelasObj->id,
                    'hari' => $randomHari,
                    'jam_mulai' => $randomJam[0],
                    'jam_selesai' => $randomJam[1],
                ]);
            }
        }
    }
}
