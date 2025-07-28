<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $fillable = ['nama_kelas', 'tingkat', 'guru_id'];

    /**
     * Relasi one-to-many ke model Siswa.
     * Satu kelas memiliki banyak siswa.
     */
    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    /**
     * Relasi one-to-many ke model Jadwal.
     * Satu kelas memiliki banyak jadwal pelajaran.
     */
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}
