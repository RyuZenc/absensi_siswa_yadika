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
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Pastikan file CSV Anda memiliki header:
        // nama_lengkap, nip, email, username(opsional), password

        $user = null;

        DB::transaction(function () use ($row, &$user) {
            $username = $row['username'] ?? null;

            $user = User::create([
                'name'     => $row['nama_lengkap'],
                'username' => $username, // boleh null
                'email'    => $row['email'],
                'password' => Hash::make($row['password']),
                'role'     => 'guru',
            ]);

            $user->guru()->create([
                'nama_lengkap'  => $row['nama_lengkap'],
                'nip'           => $row['nip'],
            ]);
        });

        return $user ? $user->guru : null;
    }
}
