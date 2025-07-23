<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class SiswaImport implements ToModel, WithHeadingRow
{
    /**
     * Properti untuk menyimpan nama kelas yang tidak ditemukan.
     * @var array
     */
    private $notFoundClasses = [];

    /**
     * Properti untuk menghitung jumlah baris yang berhasil diimpor.
     * @var int
     */
    private $importedRowCount = 0;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $kelasParts = explode(' - ', trim($row['nama_kelas']));

        if (count($kelasParts) !== 2) {
            if (!in_array($row['nama_kelas'], $this->notFoundClasses)) {
                $this->notFoundClasses[] = $row['nama_kelas'];
            }
            return null;
        }

        $tingkat = $kelasParts[0];
        $nama_kelas = $kelasParts[1];

        // Cari kelas berdasarkan tingkat dan nama_kelas
        $kelas = Kelas::where('tingkat', $tingkat)
            ->where('nama_kelas', $nama_kelas)
            ->first();

        if (!$kelas) {
            if (!in_array($row['nama_kelas'], $this->notFoundClasses)) {
                $this->notFoundClasses[] = $row['nama_kelas'];
            }
            return null;
        }

        $user = null;
        DB::transaction(function () use ($row, $kelas, &$user) {
            $user = User::create([
                'name'     => $row['nama_lengkap'],
                'email'    => $row['email'],
                'password' => Hash::make($row['password']),
                'role'     => 'siswa',
            ]);

            $user->siswa()->create([
                'nama_lengkap' => $row['nama_lengkap'],
                'nis'          => $row['nis'],
                'kelas_id'     => $kelas->id,
            ]);

            $this->importedRowCount++;
        });

        return $user ? $user->siswa : null;
    }

    /**
     * Method untuk mendapatkan daftar kelas yang tidak ditemukan.
     * @return array
     */
    public function getNotFoundClasses(): array
    {
        return $this->notFoundClasses;
    }

    /**
     * Method untuk mendapatkan jumlah baris yang berhasil diimpor.
     * @return int
     */
    public function getImportedRowCount(): int
    {
        return $this->importedRowCount;
    }
}
