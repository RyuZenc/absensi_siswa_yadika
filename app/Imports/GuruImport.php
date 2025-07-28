<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuruImport implements ToModel, WithHeadingRow
{
    private $importedRowCount = 0;
    private $updatedRowCount = 0;

    public function model(array $row)
    {
        if (!isset($row['kode_guru']) || empty($row['kode_guru'])) {
            return null;
        }

        $email = isset($row['email']) && !empty($row['email']) ? $row['email'] : null;
        $username = isset($row['username']) && !empty($row['username']) ? $row['username'] : null;
        $password = $row['password'] ?? null;
        if (empty($password)) {
            return null;
        }

        return DB::transaction(function () use ($row, $email, $username, $password) {
            $existingGuru = Guru::where('kode_guru', $row['kode_guru'])->first();

            if ($existingGuru) {
                $user = $existingGuru->user;

                if ($user) {
                    $userDataToUpdate = [
                        'name'     => $row['nama_lengkap'],
                        'email'    => $email,
                        'password' => Hash::make($password),
                    ];

                    if ($username !== null && $user->username !== $username) {
                        $userDataToUpdate['username'] = $username;
                    } elseif ($user->username === null && $username !== null) {
                        $userDataToUpdate['username'] = $username;
                    }

                    $user->update($userDataToUpdate);
                } else {
                    $user = User::create([
                        'name'     => $row['nama_lengkap'],
                        'email'    => $email,
                        'username' => $username,
                        'password' => Hash::make($password),
                        'role'     => 'guru',
                    ]);
                    $existingGuru->user_id = $user->id;
                    $existingGuru->save();
                }

                $existingGuru->update([
                    'nama_lengkap'  => $row['nama_lengkap'],
                    'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
                    'tanggal_lahir' => $row['tanggal_lahir'] ?? null,
                    'alamat'        => $row['alamat'] ?? null,
                    'no_telepon'    => $row['no_telepon'] ?? null,
                ]);

                $this->updatedRowCount++;
                return $existingGuru;
            } else {
                if ($email && User::where('email', $email)->exists()) {
                    return null;
                }

                if ($username && User::where('username', $username)->exists()) {
                    return null;
                }

                $user = User::create([
                    'name'     => $row['nama_lengkap'],
                    'email'    => $email,
                    'username' => $username,
                    'password' => Hash::make($password),
                    'role'     => 'guru',
                ]);

                $guru = $user->guru()->create([
                    'kode_guru'     => $row['kode_guru'],
                    'nama_lengkap'  => $row['nama_lengkap'],
                    'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
                    'tanggal_lahir' => $row['tanggal_lahir'] ?? null,
                    'alamat'        => $row['alamat'] ?? null,
                    'no_telepon'    => $row['no_telepon'] ?? null,
                ]);

                $this->importedRowCount++;
                return $guru;
            }
        });
    }

    public function getImportedRowCount(): int
    {
        return $this->importedRowCount;
    }

    public function getUpdatedRowCount(): int
    {
        return $this->updatedRowCount;
    }
}
